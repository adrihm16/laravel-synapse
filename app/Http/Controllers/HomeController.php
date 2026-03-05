<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        // For the homepage, we can fetch some featured products. 
        // Based on the DB seed, these are the default products.
        $destacados = Producto::with(['variantes' => function($query) {
            $query->orderBy('precio', 'asc');
        }])->take(4)->get();
        
        $categorias = Categoria::all();

        return view('home', compact('destacados', 'categorias'));
    }
}
