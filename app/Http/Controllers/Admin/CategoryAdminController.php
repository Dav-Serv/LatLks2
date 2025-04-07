<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $models = Category::paginate(5);
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Category/Index', [
            'models' => $models
        ]);
        }else{
            abort(401);
        }
    }

    public function create()
    {
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Category/Create', [
            'form_type' => 'POST',
            'title' => 'Create Category',
            'model' => [],
            'route_url' => route('categories.store'),
        ]);
        }else{
            abort(401);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Category/Create', [
            'form_type' => 'PUT',
            'title' => 'Edit Category',
            'model' => $category,
            'route_url' => route('categories.update', $category->id),
        ]);
        }else{
            abort(401);
        }
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'      => 'required',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index');
    }
}

