<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Producto;
use App\Models\Categoria;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch filter options for the sidebar (cached — filter UI is invariant per data set)
        $categorias = Cache::remember('catalog.categorias', 3600, fn () => Categoria::orderBy('nombre')->get());
        $brands = Cache::remember('catalog.brands', 3600, fn () =>
            Producto::whereNotNull('brand')->where('brand', '!=', '')->distinct()->pluck('brand')->sort()->values()
        );

        // 2. Build the query
        $query = Producto::with([
            'variantes.valores',
            'gruposOpciones.valores',
            'imagenes',
            'todasImagenes',
            'categoria',
        ]);

        // Filter by category
        if ($request->filled('categories')) {
            $categories = (array) $request->input('categories');
            $query->whereIn('id_categoria', $categories);
        }

        // Filter by brand
        if ($request->filled('brands')) {
            $selectedBrands = (array) $request->input('brands');
            $query->whereIn('brand', $selectedBrands);
        }

        // Filter: only featured products
        if ($request->boolean('featured')) {
            $query->where('destacado', true);
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->filled('min_price') ? (float) $request->input('min_price') : 0;
            $maxPrice = $request->filled('max_price') ? (float) $request->input('max_price') : null;

            $query->whereHas('variantes', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice > 0 && $maxPrice !== null) {
                    $q->whereBetween('precio', [$minPrice, $maxPrice]);
                } elseif ($minPrice > 0) {
                    $q->where('precio', '>=', $minPrice);
                } else {
                    $q->where('precio', '<=', $maxPrice);
                }
            });
        }

        // Apply sorting
        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->withMin('variantes', 'precio')->orderBy('variantes_min_precio', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->withMax('variantes', 'precio')->orderBy('variantes_max_precio', 'desc');
            } elseif ($request->sort === 'newest') {
                $query->orderBy('productos.created_at', 'desc');
            }
        } else {
            // Default sort could be newest or a specific order
            $query->orderBy('productos.created_at', 'desc');
        }

        $productos = $query->paginate(12);

        return view('catalog.index', compact('productos', 'categorias', 'brands'));
    }

    public function show($id)
    {
        $producto = Producto::with([
            'variantes.valores',
            'gruposOpciones.valores',
            'imagenes',
            'todasImagenes',
        ])->findOrFail($id);

        // Build per-color gallery map: { id_valor => [url, url, ...] }
        $colorGalleries = \App\Models\ImagenProducto::where('id_producto', $producto->id_producto)
            ->whereNotNull('id_valor')
            ->orderBy('orden')
            ->get()
            ->groupBy('id_valor')
            ->map(fn ($imgs) => $imgs->map(fn ($i) => $i->url)->values()->toArray())
            ->toArray();

        $relacionados = Producto::with(['variantes.valores', 'gruposOpciones.valores'])
            ->where('id_categoria', $producto->id_categoria)
            ->where('id_producto', '!=', $id)
            ->take(4)->get();

        return view('products.show', compact('producto', 'relacionados', 'colorGalleries'));
    }
}
