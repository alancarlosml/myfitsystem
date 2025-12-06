<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Models\Category;
use App\Models\Establishment;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HasEstablishmentContext;

    public function index(Request $request)
    {
        $establishmentId = $this->getEstablishmentId();
        $query = Category::with('establishment');
        
        // For superuser: show all categories
        if ($this->hasAnyRole(['superuser'])) {
            $query->orderBy('name');
        } else {
            // For admin: show system categories (establishment_id IS NULL) + own categories
            if ($establishmentId) {
                $query->where(function($q) use ($establishmentId) {
                    $q->whereNull('establishment_id') // System categories
                      ->orWhere('establishment_id', $establishmentId); // Own categories
                })->orderBy('name');
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status == 'ativo' ? 1 : 0);
        }

        // Date range filter
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $categories = $query->get();
        
        // Categories available for selection (system categories only)
        $categories_admin = Category::with('establishment')
                            ->whereNull('establishment_id') // Only system categories
                            ->where('active', 1)
                            ->orderBy('name')->get();
        
        $establishment = null;
        if (!$this->hasAnyRole(['superuser'])) {
            if ($establishmentId) {
                $establishment = Establishment::with('categories')->find($establishmentId);
            }
        }
                                      
        return view('admin.categories.index', [
            'categories' => $categories, 
            'categories_admin' => $categories_admin, 
            'establishment' => $establishment,
            'filters' => $request->only(['search', 'status', 'created_from', 'created_to'])
        ]);
    }

    public function create()
    {
        $establishmentId = $this->getEstablishmentId();

        return view('admin.categories.add', ['establishmentId' => $establishmentId]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        // Se for superuser, não definir establishment_id (sistema)
        // Se for admin, definir establishment_id do estabelecimento atual
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                $validatedData['establishment_id'] = $establishmentId;
            } else {
                return redirect()->route('admin.categories.index')->with('error', 'Estabelecimento não selecionado.');
            }
        } else {
            // Super user cria sem establishment_id (sistema)
            $validatedData['establishment_id'] = null;
        }

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit($category)
    {
        $category = Category::find($category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);

        // Verificar acesso: admin só pode editar suas próprias categorias
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($category->establishment_id !== $establishmentId) {
                return redirect()->route('admin.categories.index')->with('error', 'Você não tem permissão para editar esta categoria.');
            }
        }

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        // Não permitir alterar establishment_id se não for superuser
        if (!$this->hasAnyRole(['superuser'])) {
            unset($validatedData['establishment_id']);
        } else {
            // Super user pode definir como null (sistema)
            if (!isset($validatedData['establishment_id'])) {
                $validatedData['establishment_id'] = null;
            }
        }

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function view($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        return view('admin.categories.view', ['category' => $category]);
    }

    public function destroy($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        // Verificar acesso: admin só pode deletar suas próprias categorias
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($category->establishment_id !== $establishmentId) {
                return redirect()->route('admin.categories.index')->with('error', 'Você não tem permissão para deletar esta categoria.');
            }
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')->with('success', 'Categoria deletada com sucesso!');
    }

    public function restore($categoryId)
    {
        $category = Category::withTrashed()->findOrFail($categoryId);
        $category->restore();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria restaurada com sucesso.');
    }

    public function attach(Request $request)
    {
        $establishmentId = $this->getEstablishmentId();

        if (!$establishmentId) {
            return redirect()->route('admin.categories.index')->with('error', 'Estabelecimento não selecionado.');
        }

        $categories = $request->input('categories');

        $establishment = Establishment::findOrFail($establishmentId);
        $establishment->categories()->sync($categories);

        return redirect()->route('admin.categories.index')->with('success', 'Categorias atribuidas com sucesso.');
    }
}
