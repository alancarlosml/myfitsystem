<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use App\Models\Establishment;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExerciseController extends Controller
{
    protected $role;
    public function __construct()
    {
        $this->role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
    }

    public function index()
    {
        $query = Exercise::select('exercises.*')
                             ->leftJoin('establishments', 'exercises.establishment_id', '=', 'establishments.id')
                             ->leftJoin('categories', 'exercises.category_id', '=', 'categories.id')
                             ->orderBy('establishments.name')
                             ->with(['establishment', 'category']);
        
        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $query->where('establishments.id', Session::get('establishment_id'));
        }

        $exercises = $query->get();
                                      
        return view('admin.exercises.index', ['exercises' => $exercises]);
    }

    public function create()
    {
        $establishments = null;
        $categories = null;

        if ($this->role && !in_array($this->role->name, ['superuser'])) {
            $establishment = Establishment::where('id', Session::get('establishment_id'))->first(); // Alterado para first()
            if ($establishment) {
                $categories = $establishment->categories;
            }
        } else {
            $establishments = Establishment::all();
            $categories = Category::all();
        }

        return view('admin.exercises.add', ['establishments' => $establishments, 'categories' => $categories]);
    }

    public function store(StoreExerciseRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $validatedData['establishment_id'] = Session::get('establishment_id');
        }

        Exercise::create($validatedData);

        return redirect()->route('admin.exercises.index')->with('success', 'Exercício criado com sucesso!');
    }

    public function edit($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $establishments = Establishment::all();
        $categories = Category::all();

        return view('admin.exercises.edit', ['exercise' => $exercise, 'establishments' => $establishments, 'categories' => $categories]);
    }

    public function update(UpdateExerciseRequest $request, $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $validatedData['establishment_id'] = Session::get('establishment_id');
        }

        $exercise->update($validatedData);

        return redirect()->route('admin.exercises.index')->with('success', 'Exercício atualizado com sucesso!');
    }

    public function view($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        
        return view('admin.exercises.view', ['exercise' => $exercise]);
    }

    public function destroy($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->delete();

        return redirect()->route('admin.exercises.index')->with('success', 'Exercício excluído com sucesso!');
    }

    public function restore($exerciseId)
    {
        $exercise = Exercise::withTrashed()->findOrFail($exerciseId);
        $exercise->restore();

        return redirect()->route('admin.exercises.index')->with('success', 'Exercício restaurado com sucesso.');
    }
}
