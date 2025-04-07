<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TableAdminController extends Controller
{
    public function index()
    {
        $models = Table::paginate(5);
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Table/Index', [
            'models' => $models
        ]);
        }else{
            abort(401);
        }
    }

    public function create()
    {
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Table/Create', [
            'form_type' => 'POST',
            'title' => 'Create Table',
            'model' => [],
            'route_url' => route('tables.store'),
        ]);
        }else{
            abort(401);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:150',
            'location'      => 'required',
            'limit'      => 'required',
            'price'      => 'required',
            'status'    => 'required|in:active,inactive',
        ]);

        Table::create($request->all());

        return redirect()->route('tables.index');
    }

    public function edit(Table $table)
    {
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Table/Create', [
            'form_type' => 'PUT',
            'title' => 'Edit Table',
            'model' => $table,
            'route_url' => route('tables.update', $table->id),
        ]);
        }else{
            abort(401);
        }
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name'      => 'required|max:150',
            'location'      => 'required',
            'limit'      => 'required',
            'price'      => 'required',
            'status'    => 'required|in:active,inactive',
        ]);

        $table->update($request->all());

        return redirect()->route('tables.index');
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('tables.index');
    }
}
