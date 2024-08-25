<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('categories.*')
                            ->orderBy('categories.name')->get();
        
        $categories_admin = Category::select('categories.*')
                            ->where('active', 1)
                            ->orderBy('categories.name')->get();
                        
        $establishment = Establishment::with('categories')->findOrFail(Session::get('establishment_id'));
                                      
        return view('admin.categories.index', ['categories' => $categories, 'categories_admin' => $categories_admin, 'establishment' => $establishment]);
    }

    public function create()
    {
        return view('admin.categories.add');
    }

    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
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

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
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
        $category->delete();
    }

    public function restore($categoryId)
    {
        $category = Category::withTrashed()->findOrFail($categoryId);
        $category->restore();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria restaurada com sucesso.');
    }

    public function attach(Request $request)
    {
        $establishmentId = Session::get('establishment_id');

        $categories = $request->input('categories');

        $establishment = Establishment::findOrFail($establishmentId);
        $establishment->categories()->sync($categories);

        return redirect()->route('admin.categories.index')->with('success', 'Categorias atribuidas com sucesso.');
    }
}
