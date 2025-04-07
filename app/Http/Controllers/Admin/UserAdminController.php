<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAdminController extends Controller
{
    public function index(){
        $models = User::paginate(5);
        if(Auth::user()->level == 'admin'){
        $models->getCollection()->makeVisible('password');
        return Inertia::render('User/Index', [
            'models' => $models
        ]);
        }else{
            abort(401);
        }
    }

    public function create()
    {
        if(Auth::user()->level == 'admin'){
        return Inertia::render('User/Create', [
            'form_type' => 'POST',
            'title' => 'Create User',
            'model' => [],
            'route_url' => route('user.store'),
        ]);
        }else{
            abort(401);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'level'     => 'required',
            'password'  => 'required|min:8',
        ]);

        User::create($request->all());

        return redirect()->route('user.index');
    }

    public function edit(User $user)
    {
        if(Auth::user()->level == 'admin'){
        return Inertia::render('User/Create', [
            'form_type' => 'PUT',
            'title' => 'Edit User',
            'model' => $user,
            'route_url' => route('user.update', $user->id),
        ]);
        }else{
            abort(401);
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'level'     => 'required',
            'password'  => 'nullable',
        ]);

        $user->update($request->all());

        return redirect()->route('user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index');
    }
}
