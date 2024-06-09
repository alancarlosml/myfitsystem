<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::select('categories.*')
                                      ->leftJoin('establishments', 'categories.establishment_id', '=', 'establishments.id')
                                      ->orderBy('establishments.name')
                                      ->with('establishment')
                                      ->get();
                                      
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
