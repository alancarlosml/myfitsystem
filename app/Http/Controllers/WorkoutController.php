<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Establishment;
use App\Models\User;
use App\Models\Student;
use App\Models\Exercise;
use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::select('workouts.*')
                             ->leftJoin('establishments', 'workouts.establishment_id', '=', 'establishments.id')
                             ->leftJoin('users', 'workouts.user_id', '=', 'users.id')
                             ->leftJoin('students', 'workouts.student_id', '=', 'students.id')
                             ->leftJoin('exercises', 'workouts.exercise_id', '=', 'exercises.id')
                             ->orderBy('establishments.name')
                             ->with(['establishment', 'user', 'student', 'exercise'])
                             ->get();

        return view('admin.workouts.index', ['workouts' => $workouts]);
    }

    public function create()
    {
        $establishments = Establishment::all();
        $users = User::all();
        $students = Student::all();
        $exercises = Exercise::all();

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

        Workout::create($validatedData);

        return redirect()->route('admin.workouts.index')->with('success', 'Treino criado com sucesso!');
    }

    public function edit($workoutId)
    {
        $workout = Workout::findOrFail($workoutId);
        $establishments = Establishment::all();
        $users = User::all();
        $students = Student::all();
        $exercises = Exercise::all();

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
}
