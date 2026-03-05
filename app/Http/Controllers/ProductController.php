<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductController extends Controller
{
    public function index()
    {
        // Render catalog
        $productos = Producto::with('variantes')->get();
        return view('catalog.index', compact('productos'));
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
