<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasEstablishmentContext;
use App\Models\StudentGoal;
use App\Models\Student;
use App\Services\GoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentGoalController extends Controller
{
    use HasEstablishmentContext;

    protected $goalService;

    public function __construct(GoalService $goalService)
    {
        $this->goalService = $goalService;
    }

    /**
     * List goals for a student (admin view)
     */
    public function index($studentId)
    {
        $establishmentId = $this->getEstablishmentId();
        $student = Student::findOrFail($studentId);
        
        // Verify access
        if (!$this->hasAnyRole(['superuser'])) {
            // Check if student belongs to current establishment
            if (!$student->establishments->contains($establishmentId)) {
                abort(403, 'Acesso negado');
            }
        }

        $goals = StudentGoal::where('student_id', $studentId)
            ->when($establishmentId && !$this->hasAnyRole(['superuser']), function($q) use ($establishmentId) {
                $q->where(function($query) use ($establishmentId) {
                    $query->where('establishment_id', $establishmentId)->orWhereNull('establishment_id');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.students.goals', [
            'student' => $student,
            'goals' => $goals,
        ]);
    }

    /**
     * List goals for current student (student view)
     */
    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();
        $establishmentId = Session::get('establishment_id');
        
        $goals = $this->goalService->getActiveGoals($studentId, $establishmentId);
        $statistics = $this->goalService->getGoalStatistics($studentId, $establishmentId);

        return view('student.goals.index', [
            'goals' => $goals,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Show form to create a new goal
     */
    public function create($studentId = null)
    {
        $studentId = $studentId ?? Auth::guard('student')->id();
        $student = Student::findOrFail($studentId);
        $establishmentId = $this->getEstablishmentId() ?? Session::get('establishment_id');

        return view('admin.students.goals.create', [
            'student' => $student,
            'establishmentId' => $establishmentId,
        ]);
    }

    /**
     * Store a new goal
     */
    public function store(Request $request, $studentId = null)
    {
        $studentId = $studentId ?? Auth::guard('student')->id();
        $student = Student::findOrFail($studentId);
        
        $validated = $request->validate([
            'goal_type' => 'required|in:weekly_classes,monthly_classes,workouts,weekly_workouts,monthly_workouts,weight_loss,weight_gain,custom',
            'goal_name' => 'required|string|max:255',
            'target_value' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'establishment_id' => 'nullable|exists:establishments,id',
        ]);

        $establishmentId = $validated['establishment_id'] ?? $this->getEstablishmentId() ?? Session::get('establishment_id');

        $goal = $this->goalService->createGoal([
            'student_id' => $studentId,
            'establishment_id' => $establishmentId,
            'goal_type' => $validated['goal_type'],
            'goal_name' => $validated['goal_name'],
            'target_value' => $validated['target_value'],
            'current_value' => 0,
            'unit' => $validated['unit'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'active' => true,
            'achieved' => false,
        ]);

        // Sync progress for the new goal
        $this->goalService->syncAllGoalsProgress($studentId, $establishmentId);

        if (Auth::guard('student')->check()) {
            return redirect()->route('student.goals.index')
                ->with('success', 'Meta criada com sucesso!');
        }

        return redirect()->route('admin.students.goals', $studentId)
            ->with('success', 'Meta criada com sucesso!');
    }

    /**
     * Show form to edit a goal
     */
    public function edit($goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        
        // Verify access
        if (Auth::guard('student')->check()) {
            $studentId = Auth::guard('student')->id();
            if ($goal->student_id !== $studentId) {
                abort(403, 'Acesso negado');
            }
        } elseif (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($goal->establishment_id && $goal->establishment_id !== $establishmentId) {
                abort(403, 'Acesso negado');
            }
        }

        return view('admin.students.goals.edit', [
            'goal' => $goal,
        ]);
    }

    /**
     * Update a goal
     */
    public function update(Request $request, $goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        
        // Verify access
        if (Auth::guard('student')->check()) {
            $studentId = Auth::guard('student')->id();
            if ($goal->student_id !== $studentId) {
                abort(403, 'Acesso negado');
            }
        } elseif (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($goal->establishment_id && $goal->establishment_id !== $establishmentId) {
                abort(403, 'Acesso negado');
            }
        }

        $validated = $request->validate([
            'goal_name' => 'required|string|max:255',
            'target_value' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        $goal->update($validated);

        // Recalculate progress if needed
        if ($goal->active) {
            $this->goalService->syncAllGoalsProgress($goal->student_id, $goal->establishment_id);
        }

        if (Auth::guard('student')->check()) {
            return redirect()->route('student.goals.index')
                ->with('success', 'Meta atualizada com sucesso!');
        }

        return redirect()->route('admin.students.goals', $goal->student_id)
            ->with('success', 'Meta atualizada com sucesso!');
    }

    /**
     * Delete a goal
     */
    public function destroy($goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        $studentId = $goal->student_id;
        
        // Verify access
        if (Auth::guard('student')->check()) {
            if ($goal->student_id !== Auth::guard('student')->id()) {
                abort(403, 'Acesso negado');
            }
        } elseif (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($goal->establishment_id && $goal->establishment_id !== $establishmentId) {
                abort(403, 'Acesso negado');
            }
        }

        $goal->delete();

        if (Auth::guard('student')->check()) {
            return redirect()->route('student.goals.index')
                ->with('success', 'Meta excluída com sucesso!');
        }

        return redirect()->route('admin.students.goals', $studentId)
            ->with('success', 'Meta excluída com sucesso!');
    }

    /**
     * Manually sync goal progress (AJAX)
     */
    public function syncProgress($goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        
        // Verify access
        if (Auth::guard('student')->check()) {
            if ($goal->student_id !== Auth::guard('student')->id()) {
                abort(403, 'Acesso negado');
            }
        }

        $this->goalService->syncAllGoalsProgress($goal->student_id, $goal->establishment_id);
        $goal->refresh();

        return response()->json([
            'success' => true,
            'goal' => $goal,
            'progress_percentage' => $goal->progress_percentage,
        ]);
    }
}
