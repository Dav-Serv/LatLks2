<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

class WelcomeController extends Controller
{
    public function index(){
        $models = Product::all();
        // $level = Auth::user()->level;
        return Inertia::render('Welcome', [
            // 'name'      => Auth::user()->name,
            'canLogin'      => Route::has('login'),
            'canRegister'   => Route::has('register'),
            'models'        => $models,
        ]);
    }

    public function show($product){
        $models = Product::with('category')->findOrFail($product);

        return Inertia::render('Welcome/Show', [
            'models'    => $models
        ]);
    }
}
