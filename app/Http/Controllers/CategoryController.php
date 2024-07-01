<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    protected $role;

    public function __construct()
    {
        $this->role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
    }

    public function index()
    {
        $query = \App\Models\Category::select('categories.*')
                          ->leftJoin('establishments', 'categories.establishment_id', '=', 'establishments.id')
                          ->orderBy('establishments.name')
                          ->with('establishment');

        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $query->where('establishments.id', Session::get('establishment_id'));
        }

        $categories = $query->get();
                                      
        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        $establishments = \App\Models\Establishment::all();

        return view('admin.categories.add', ['establishments'=> $establishments]);
    }

    public function store(StoreCategoryRequest $request)
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

        \App\Models\Category::create($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit($category)
    {
        $category = \App\Models\Category::find($category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, $categoryId)
    {
        $category = \App\Models\Category::findOrFail($categoryId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $validatedData['establishment_id'] = Session::get('establishment_id');
        }

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function view($categoryId)
    {
        $category = \App\Models\Category::findOrFail($categoryId);
        
        return view('admin.categories.view', ['category' => $category]);
    }

    public function destroy($categoryId)
    {
        $category = \App\Models\Category::findOrFail($categoryId);
        $category->delete();
    }

    public function restore($categoryId)
    {
        $category = \App\Models\Category::withTrashed()->findOrFail($categoryId);
        $category->restore();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria restaurada com sucesso.');
    }
}
