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
        $query = Producto::with('variantes');

        // Filter by category
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('id_categoria', $request->categories);
        }

        // Filter by brand
        if ($request->has('brands') && is_array($request->brands)) {
            $query->whereIn('brand', $request->brands);
        }

        // Filter by price range
        // Since price is stored in VarianteProducto, we need a whereHas query
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->input('min_price', 0);
            $maxPrice = $request->input('max_price', 999999);
            
            $query->whereHas('variantes', function($q) use ($minPrice, $maxPrice) {
                // Determine which condition to apply to optimize the query
                if ($minPrice > 0 && $maxPrice < 999999) {
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

        $productos = $query->get();

        return view('catalog.index', compact('productos', 'categorias', 'brands'));
    }

    public function show($id)
    {
        // Render single product page
        $producto = Producto::with(['variantes', 'imagenes'])->findOrFail($id);
        
        // Also fetch related products
        $relacionados = Producto::with('variantes')
            ->where('id_categoria', $producto->id_categoria)
            ->where('id_producto', '!=', $id)
            ->take(4)->get();

        return view('products.show', compact('producto', 'relacionados'));
    }
}
