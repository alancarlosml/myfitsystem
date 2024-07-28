<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use App\Models\Establishment;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


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
                             ->orderBy('categories.name')
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
            $establishment = Establishment::where('id', Session::get('establishment_id'))->first(); 
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

        if ($request->hasFile('exercise_picture')) {
            $file = $request->file('exercise_picture');
            $fileName = time() . '_' . $file->getClientOriginalName();
    
            $destinationPath = 'exercise_pictures';
    
            // Verificar se o diretório de destino existe
            if (!Storage::disk('public')->exists($destinationPath)) {
                Storage::disk('public')->makeDirectory($destinationPath);
            }
    
            // Salvar o arquivo no diretório de destino
            $file->storeAs($destinationPath, $fileName, 'public');
    
            $validatedData['exercise_picture'] = 'exercise_pictures/' . $fileName;
        }

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
        $establishments = null;
        $categories = null;

        if ($this->role && !in_array($this->role->name, ['superuser'])) {
            $establishment = Establishment::where('id', Session::get('establishment_id'))->first(); 
            if ($establishment) {
                $categories = $establishment->categories;
            }
        } else {
            $establishments = Establishment::all();
            $categories = Category::all();
        }

        return view('admin.exercises.edit', ['exercise' => $exercise, 'establishments' => $establishments, 'categories' => $categories]);
    }

    public function update(UpdateExerciseRequest $request, $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);

        $validatedData = $request->validated();

        if ($request->hasFile('exercise_picture')) {
            $file = $request->file('exercise_picture');
            $fileName = time() . '_' . $file->getClientOriginalName();
    
            $destinationPath = 'exercise_pictures';
    
            // Verificar se o diretório de destino existe
            if (!Storage::disk('public')->exists($destinationPath)) {
                Storage::disk('public')->makeDirectory($destinationPath);
            }
    
            // Salvar o arquivo no diretório de destino
            $file->storeAs($destinationPath, $fileName, 'public');
    
            $validatedData['exercise_picture'] = 'exercise_pictures/' . $fileName;
        }

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

    public function removeExercisePicture($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->exercise_picture = null;
        $exercise->save();

        $filePath = public_path($exercise->exercise_picture);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return response()->json([
            'message' => 'Imagem do exercício removida com sucesso.',
        ]);
    }
}
