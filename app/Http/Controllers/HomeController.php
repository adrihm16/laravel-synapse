<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Producto;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $destacados = Cache::remember('home.featured_products', 600, function () {
            return Producto::with([
                'variantes' => fn ($q) => $q->orderBy('precio', 'asc'),
                'gruposOpciones.valores',
                'imagenes',
                'todasImagenes',
            ])
            ->where('destacado', true)
            ->orderBy('id_producto', 'desc')
            ->take(4)
            ->get();
        });

        $categorias = Cache::remember('home.categorias', 3600, fn () => Categoria::all());

        return view('home', compact('destacados', 'categorias'));
    }
}
