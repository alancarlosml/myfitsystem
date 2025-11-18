<?php

namespace App\Http\Controllers;

use App\Models\WorkoutLog;
use App\Models\Workout;
use App\Models\Student;
use App\Models\Establishment;
use App\Http\Traits\HasEstablishmentContext;
use Illuminate\Http\Request;

class WorkoutLogController extends Controller
{
    use HasEstablishmentContext;

    public function index()
    {
        $query = WorkoutLog::with(['workout', 'student', 'exercise', 'establishment'])
                              ->orderBy('date', 'desc');

        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                // Optimized: Filter by establishment_id directly (already indexed)
                $query->where('workout_logs.establishment_id', $establishmentId);
            } else {
                $query->whereRaw('1 = 0'); // Return empty if no establishment
            }
        }

        $workoutLogs = $query->get();

        return view('admin.workout_logs.index', ['workoutLogs' => $workoutLogs]);
    }

    public function create()
    {
        $workouts = Workout::all();
        $students = null;

        if ($this->hasAnyRole(['superuser'])) {
            $students = Student::all();
        } else {
            $students = $this->getAvailableStudents();
        }

        return view('admin.workout_logs.add', ['workouts' => $workouts, 'students' => $students]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
        ]);

        // Vincular ao estabelecimento do admin logado (se não for superuser)
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                $validatedData['establishment_id'] = $establishmentId;
            }
        }

        WorkoutLog::create($validatedData);

        return redirect()->route('admin.workout_logs.index')->with('success', 'Log de treino criado com sucesso!');
    }

    public function edit($workoutLogId)
    {
        $workoutLog = WorkoutLog::findOrFail($workoutLogId);
        $workouts = Workout::all();
        $students = null;

        if ($this->hasAnyRole(['superuser'])) {
            $students = Student::all();
        } else {
            $students = $this->getAvailableStudents();
        }

        return view('admin.workout_logs.edit', ['workoutLog' => $workoutLog, 'workouts' => $workouts, 'students' => $students]);
    }

    public function update(Request $request, $workoutLogId)
    {
        $workoutLog = WorkoutLog::findOrFail($workoutLogId);

        $validatedData = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
        ]);

        $workoutLog->update($validatedData);

        return redirect()->route('admin.workout_logs.index')->with('success', 'Log de treino atualizado com sucesso!');
    }

    public function view($workoutLogId)
    {
        $workoutLog = WorkoutLog::with(['workout', 'student'])->findOrFail($workoutLogId);

        return view('admin.workout_logs.view', ['workoutLog' => $workoutLog]);
    }

    public function destroy($workoutLogId)
    {
        $workoutLog = WorkoutLog::findOrFail($workoutLogId);
        $workoutLog->delete();

        return redirect()->route('admin.workout_logs.index')->with('success', 'Log de treino excluído com sucesso!');
    }

    public function restore($workoutLogId)
    {
        $workoutLog = WorkoutLog::withTrashed()->findOrFail($workoutLogId);
        $workoutLog->restore();

        return redirect()->route('admin.workout_logs.index')->with('success', 'Log de treino restaurado com sucesso.');
    }
}
