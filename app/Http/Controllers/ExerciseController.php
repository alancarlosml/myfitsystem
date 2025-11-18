<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Models\Exercise;
use App\Models\Establishment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ExerciseController extends Controller
{
    use HasEstablishmentContext;

    public function index(Request $request)
    {
        $query = Exercise::select('exercises.*')
                             ->leftJoin('establishments', 'exercises.establishment_id', '=', 'establishments.id')
                             ->leftJoin('categories', 'exercises.category_id', '=', 'categories.id')
                             ->orderBy('establishments.name')
                             ->orderBy('categories.name')
                             ->with(['establishment', 'category']);
        
        // Apply establishment filter if not superuser
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                $query->where('establishments.id', $establishmentId);
            } else {
                $query->whereRaw('1 = 0'); // Return empty if no establishment
            }
        } else {
            // Filter by establishment if provided (superuser)
            if ($request->filled('establishment_id')) {
                $query->where('establishments.id', $request->establishment_id);
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('exercises.name', 'like', "%{$search}%")
                  ->orWhere('exercises.description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('exercises.active', $request->status == 'ativo' ? 1 : 0);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('exercises.category_id', $request->category_id);
        }

        // Date range filter
        if ($request->filled('created_from')) {
            $query->whereDate('exercises.created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('exercises.created_at', '<=', $request->created_to);
        }

        $exercises = $query->get();

        // Get establishments for filter dropdown (superuser only)
        $establishments = collect([]);
        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::orderBy('name')->get();
        }

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();
                                      
        return view('admin.exercises.index', [
            'exercises' => $exercises,
            'establishments' => $establishments,
            'categories' => $categories,
            'filters' => $request->only(['search', 'status', 'category_id', 'establishment_id', 'created_from', 'created_to'])
        ]);
    }

    public function create()
    {
        $establishments = null;
        $categories = null;

        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::all();
            $categories = Category::all();
        } else {
            $establishmentId = $this->getEstablishmentId();
            $establishment = $establishmentId ? Establishment::where('id', $establishmentId)->first() : null;
            if ($establishment) {
                $categories = $establishment->categories;
            }
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

        if (!$this->hasAnyRole(['superuser'])){
            $validatedData['establishment_id'] = $this->getEstablishmentId();
        }

        Exercise::create($validatedData);

        return redirect()->route('admin.exercises.index')->with('success', 'Exercício criado com sucesso!');
    }

    public function edit($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $establishments = null;
        $categories = null;

        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::all();
            $categories = Category::all();
        } else {
            $establishmentId = $this->getEstablishmentId();
            $establishment = $establishmentId ? Establishment::where('id', $establishmentId)->first() : null;
            if ($establishment) {
                $categories = $establishment->categories;
            }
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

        if (!$this->hasAnyRole(['superuser'])){
            $validatedData['establishment_id'] = $this->getEstablishmentId();
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
