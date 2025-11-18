<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Establishment;
use App\Models\User;
use App\Models\Student;
use App\Models\Exercise;
use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Services\WorkoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WorkoutController extends Controller
{
    use HasEstablishmentContext;

    protected $guard;
    protected $workoutService;

    public function __construct(WorkoutService $workoutService)
    {
        $this->guard = Auth::guard('user')->check() ? 'user' : 'student';
        $this->workoutService = $workoutService;
    }

    public function index()
    {
        $query = Workout::select('workouts.*')
                             ->leftJoin('establishments', 'workouts.establishment_id', '=', 'establishments.id')
                             ->leftJoin('users', 'workouts.user_id', '=', 'users.id')
                             ->leftJoin('students', 'workouts.student_id', '=', 'students.id')
                             ->leftJoin('exercises', 'workouts.exercise_id', '=', 'exercises.id')
                             ->with(['establishment', 'user', 'student', 'exercise']);

        if ($this->guard === 'student') {
            $studentId = Auth::guard('student')->id();
            $query->where('workouts.student_id', $studentId);
        } else {
            // For admin users, filter by establishment unless superuser
            if (!$this->hasAnyRole(['superuser'])) {
                $establishmentId = $this->getEstablishmentId();
                if ($establishmentId) {
                    $query->where('workouts.establishment_id', $establishmentId);
                } else {
                    // No establishment selected, return empty
                    $query->whereRaw('1 = 0');
                }
            }
            
            $query->orderBy('establishments.name');
        }

        $workouts = $query->get();

        $view = $this->guard === 'student' ? 'student.workouts.index' : 'admin.workouts.index';
        return view($view, ['workouts' => $workouts]);
    }

    public function create()
    {
        // Superuser can see all, others are restricted to their establishment
        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::all();
            $users = User::all();
            $students = Student::all();
            $exercises = Exercise::all();
        } else {
            $establishmentId = $this->getEstablishmentId();
            
            // Filter by current establishment
            $establishments = $establishmentId ? Establishment::where('id', $establishmentId)->get() : collect([]);
            
            // Users in this establishment
            $users = $this->getAvailableUsers();
            
            // Students in this establishment
            $students = $this->getAvailableStudents();
            
            // Exercises in this establishment
            $exercises = $establishmentId 
                ? Exercise::where('establishment_id', $establishmentId)->get()
                : collect([]);
        }

        return view('admin.workouts.add', [
            'establishments' => $establishments,
            'users' => $users,
            'students' => $students,
            'exercises' => $exercises
        ]);
    }

    public function store(StoreWorkoutRequest $request)
    {
        $validatedData = $request->validated();

        // Vincular ao estabelecimento do admin logado (se não for superuser)
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                $validatedData['establishment_id'] = $establishmentId;
            }
        }

        Workout::create($validatedData);

        return redirect()->route('admin.workouts.index')->with('success', 'Treino criado com sucesso!');
    }

    public function edit($workoutId)
    {
        $workout = Workout::findOrFail($workoutId);
        
        // Verify access - workout must belong to user's establishment unless superuser
        if ($this->guard === 'user') {
            $this->authorizeResourceAccess($workout);
        }
        
        // Superuser can see all, others are restricted to their establishment
        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::all();
            $users = User::all();
            $students = Student::all();
            $exercises = Exercise::all();
        } else {
            $establishmentId = $this->getEstablishmentId();
            $establishments = $establishmentId ? Establishment::where('id', $establishmentId)->get() : collect([]);
            $users = $this->getAvailableUsers();
            $students = $this->getAvailableStudents();
            $exercises = $establishmentId 
                ? Exercise::where('establishment_id', $establishmentId)->get()
                : collect([]);
        }

        return view('admin.workouts.edit', [
            'workout' => $workout,
            'establishments' => $establishments,
            'users' => $users,
            'students' => $students,
            'exercises' => $exercises
        ]);
    }

    public function update(UpdateWorkoutRequest $request, $workoutId)
    {
        $workout = Workout::findOrFail($workoutId);

        $validatedData = $request->validated();

        $workout->update($validatedData);

        return redirect()->route('admin.workouts.index')->with('success', 'Treino atualizado com sucesso!');
    }

    public function view($workoutId)
    {
        $workout = Workout::findOrFail($workoutId);
        
        return view('admin.workouts.view', ['workout' => $workout]);
    }

    public function destroy($workoutId)
    {
        $workout = Workout::findOrFail($workoutId);
        $workout->delete();

        return redirect()->route('admin.workouts.index')->with('success', 'Treino excluído com sucesso!');
    }

    public function restore($workoutId)
    {
        $workout = Workout::withTrashed()->findOrFail($workoutId);
        $workout->restore();

        return redirect()->route('admin.workouts.index')->with('success', 'Treino restaurado com sucesso.');
    }

    public function start($workoutId)
    {
        $workout = Workout::with(['student', 'user', 'exercise.category'])->findOrFail($workoutId);

        if ($this->guard === 'student') {
            $studentId = Auth::guard('student')->id();
            if ($workout->student_id !== $studentId) {
                abort(403, 'Acesso negado');
            }
        }

        $exercises = $this->workoutService->prepareExercisesForWorkout($workout);

        return view('student.workouts.start', compact('workout', 'exercises'));
    }

    public function logExercise(Request $request, $workoutId)
    {
        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'completed_sets' => 'nullable|integer|min:0',
            'completed_reps' => 'nullable|string',
            'rest_duration' => 'nullable|integer|min:0',
            'exercise_duration' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $studentId = Auth::guard('student')->id();
        $workout = Workout::findOrFail($workoutId);

        if ($workout->student_id !== $studentId) {
            abort(403, 'Acesso negado');
        }

        $this->workoutService->logExercise(
            $workoutId,
            $request->exercise_id,
            $studentId,
            [
                'completed_sets' => $request->completed_sets ?? 0,
                'completed_reps' => $request->completed_reps ?? '',
                'rest_duration' => $request->rest_duration ?? 0,
                'exercise_duration' => $request->exercise_duration ?? 0,
                'notes' => $request->notes ?? '',
                'date' => now()->toDateString(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Progresso registrado com sucesso!']);
    }
}
