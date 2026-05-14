<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch filter options for the sidebar
        $categorias = Categoria::orderBy('nombre')->get();
        // Get unique brands from products that have a brand assigned
        $brands = Producto::whereNotNull('brand')->where('brand', '!=', '')->distinct()->pluck('brand')->sort()->values();

        // 2. Build the query
        $query = Producto::with(['variantes.valores', 'gruposOpciones.valores']);

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
        // Render single product page
        $producto = Producto::with(['variantes.valores', 'gruposOpciones.valores', 'imagenes'])->findOrFail($id);
        
        // Also fetch related products
        $relacionados = Producto::with(['variantes.valores', 'gruposOpciones.valores'])
            ->where('id_categoria', $producto->id_categoria)
            ->where('id_producto', '!=', $id)
            ->take(4)->get();

        return view('products.show', compact('producto', 'relacionados'));
    }
}
