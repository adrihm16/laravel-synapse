<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\HeroBanner;

class HomeController extends Controller
{
    public function index()
    {
        $destacados = Cache::remember('home.featured_products', 600, function () {
            return Producto::with([
                'variantes' => fn ($q) => $q->orderBy('precio', 'asc'),
                'variantes.valores',
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

        $heroBanner = Cache::remember('home.hero_banner', 3600, fn () =>
            HeroBanner::where('activo', true)->orderBy('orden')->first()
        );

        return view('home', compact('destacados', 'categorias', 'heroBanner'));
    }
}
