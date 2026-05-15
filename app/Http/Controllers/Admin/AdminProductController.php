<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Services\ProductService;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a paginated listing of products with search and filters.
     */
    public function index(Request $request)
    {
        $products = Producto::with(['categoria', 'variantes'])
            ->filter($request->only(['search', 'id_categoria']))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Categoria::orderBy('nombre')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Categoria::orderBy('nombre')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product with variants and gallery images (transactional).
     */
    public function store(StoreProductRequest $request)
    {
        $this->productService->createProduct($request->validated(), $request);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Display the specified product (read-only preview).
     */
    public function show(Producto $product)
    {
        $product->load(['categoria', 'variantes', 'imagenes']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Producto $product)
    {
        $product->load(['variantes.valores', 'gruposOpciones.valores', 'imagenes']);
        $categories = Categoria::orderBy('nombre')->get();

        // Per-color gallery: { id_valor => [ImagenProducto, ...] }
        $colorImages = \App\Models\ImagenProducto::where('id_producto', $product->id_producto)
            ->whereNotNull('id_valor')
            ->orderBy('orden')
            ->get()
            ->groupBy('id_valor');

        return view('admin.products.edit', compact('product', 'categories', 'colorImages'));
    }

    /**
     * Update the specified product with variants and gallery images (transactional).
     */
    public function update(UpdateProductRequest $request, Producto $product)
    {
        $this->productService->updateProduct($product, $request->validated(), $request);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Soft-delete the specified product and its variants.
     */
    public function destroy(Producto $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
