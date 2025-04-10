<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuApiController extends Controller
{
    public function index(){
        $models = Product::with('category')->paginate(5);
        return new PostResource(true, 'List Data Product', $models);
    }
}
