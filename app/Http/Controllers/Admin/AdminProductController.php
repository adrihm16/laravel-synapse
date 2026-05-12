<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Categoria;
use App\Models\ImagenProducto;
use App\Models\Producto;
use App\Models\VarianteProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a paginated listing of products with search and filters.
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'variantes']);

        // Search by product name or brand
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->input('id_categoria'));
        }

        $products   = $query->orderBy('created_at', 'desc')->paginate(15);
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
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            // 1. Create the product
            $product = Producto::create([
                'nombre'       => $data['nombre'],
                'descripcion'  => $data['descripcion'] ?? null,
                'id_categoria' => $data['id_categoria'] ?? null,
                'brand'        => $data['brand'] ?? null,
            ]);

            // 2. Create variants
            foreach ($data['variantes'] as $index => $variantData) {
                $imagePath = null;

                if ($request->hasFile("variantes.{$index}.imagen")) {
                    $imagePath = $request->file("variantes.{$index}.imagen")
                        ->store("products/{$product->id_producto}/variants", 'public');
                }

                $product->variantes()->create([
                    'color'          => $variantData['color'],
                    'almacenamiento' => $variantData['almacenamiento'],
                    'precio'         => $variantData['precio'],
                    'stock'          => $variantData['stock'],
                    'sku'            => $variantData['sku'] ?? null,
                    'imagen'         => $imagePath,
                ]);
            }

            // 3. Upload gallery images
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $index => $file) {
                    $path = $file->store("products/{$product->id_producto}/gallery", 'public');

                    $product->imagenes()->create([
                        'ruta'  => $path,
                        'orden' => $index,
                    ]);
                }
            }
        });

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
        $product->load(['variantes', 'imagenes']);
        $categories = Categoria::orderBy('nombre')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product with variants and gallery images (transactional).
     */
    public function update(UpdateProductRequest $request, Producto $product)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request, $product) {
            // 1. Update product base info
            $product->update([
                'nombre'       => $data['nombre'],
                'descripcion'  => $data['descripcion'] ?? null,
                'id_categoria' => $data['id_categoria'] ?? null,
                'brand'        => $data['brand'] ?? null,
            ]);

            // 2. Delete removed variants
            if (!empty($data['eliminar_variantes'])) {
                $variantsToDelete = VarianteProducto::whereIn('id_variante', $data['eliminar_variantes'])
                    ->where('id_producto', $product->id_producto)
                    ->get();

                foreach ($variantsToDelete as $variant) {
                    if ($variant->imagen) {
                        Storage::disk('public')->delete($variant->imagen);
                    }
                    $variant->forceDelete();
                }
            }

            // 3. Update existing variants
            if (!empty($data['variantes_existentes'])) {
                foreach ($data['variantes_existentes'] as $index => $variantData) {
                    $variant = VarianteProducto::find($variantData['id_variante']);
                    if (!$variant || $variant->id_producto !== $product->id_producto) {
                        continue;
                    }

                    $updateData = [
                        'color'          => $variantData['color'],
                        'almacenamiento' => $variantData['almacenamiento'],
                        'precio'         => $variantData['precio'],
                        'stock'          => $variantData['stock'],
                        'sku'            => $variantData['sku'] ?? null,
                    ];

                    if ($request->hasFile("variantes_existentes.{$index}.imagen")) {
                        // Delete old image
                        if ($variant->imagen) {
                            Storage::disk('public')->delete($variant->imagen);
                        }
                        $updateData['imagen'] = $request->file("variantes_existentes.{$index}.imagen")
                            ->store("products/{$product->id_producto}/variants", 'public');
                    }

                    $variant->update($updateData);
                }
            }

            // 4. Create new variants
            if (!empty($data['variantes_nuevas'])) {
                foreach ($data['variantes_nuevas'] as $index => $variantData) {
                    $imagePath = null;

                    if ($request->hasFile("variantes_nuevas.{$index}.imagen")) {
                        $imagePath = $request->file("variantes_nuevas.{$index}.imagen")
                            ->store("products/{$product->id_producto}/variants", 'public');
                    }

                    $product->variantes()->create([
                        'color'          => $variantData['color'],
                        'almacenamiento' => $variantData['almacenamiento'],
                        'precio'         => $variantData['precio'],
                        'stock'          => $variantData['stock'],
                        'sku'            => $variantData['sku'] ?? null,
                        'imagen'         => $imagePath,
                    ]);
                }
            }

            // 5. Delete removed gallery images
            if (!empty($data['eliminar_imagenes'])) {
                $imagesToDelete = ImagenProducto::whereIn('id_imagen', $data['eliminar_imagenes'])
                    ->where('id_producto', $product->id_producto)
                    ->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->ruta);
                    $img->delete();
                }
            }

            // 6. Upload new gallery images
            if ($request->hasFile('imagenes')) {
                $maxOrder = $product->imagenes()->max('orden') ?? -1;

                foreach ($request->file('imagenes') as $file) {
                    $maxOrder++;
                    $path = $file->store("products/{$product->id_producto}/gallery", 'public');

                    $product->imagenes()->create([
                        'ruta'  => $path,
                        'orden' => $maxOrder,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Soft-delete the specified product and its variants.
     */
    public function destroy(Producto $product)
    {
        DB::transaction(function () use ($product) {
            // Soft-delete all variants first
            $product->variantes()->delete();
            // Soft-delete the product
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
