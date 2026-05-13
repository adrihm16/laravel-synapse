<?php

namespace App\Services;

use App\Models\ImagenProducto;
use App\Models\Producto;
use App\Models\GrupoOpcionProducto;
use App\Models\ValorOpcionProducto;
use App\Models\Variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Create a new product with option groups, values, variants, and gallery images.
     */
    public function createProduct(array $data, Request $request): Producto
    {
        return DB::transaction(function () use ($data, $request) {
            // 1. Create the product
            $product = Producto::create([
                'nombre'       => $data['nombre'],
                'descripcion'  => $data['descripcion'] ?? null,
                'id_categoria' => $data['id_categoria'] ?? null,
                'brand'        => $data['brand'] ?? null,
                'precio_base'  => $data['precio_base'] ?? 0,
            ]);

            // Track value IDs to link variants later
            // Format: ['GroupIndex_ValueIndex' => Real_Valor_ID]
            $valorIdMap = [];

            // 2. Create Option Groups and Values
            if (!empty($data['grupos'])) {
                foreach ($data['grupos'] as $gIndex => $grupoData) {
                    $grupo = $product->gruposOpciones()->create([
                        'nombre' => $grupoData['nombre'],
                        'tipo'   => $grupoData['tipo'] ?? 'texto',
                        'orden'  => $gIndex,
                    ]);

                    if (!empty($grupoData['valores'])) {
                        foreach ($grupoData['valores'] as $vIndex => $valorData) {
                            $imagePath = null;
                            if ($request->hasFile("grupos.{$gIndex}.valores.{$vIndex}.imagen")) {
                                $imagePath = $request->file("grupos.{$gIndex}.valores.{$vIndex}.imagen")
                                    ->store("products/{$product->id_producto}/options", 'public');
                            }

                            $valor = $grupo->valores()->create([
                                'nombre'       => $valorData['nombre'],
                                'hex_code'     => $valorData['hex_code'] ?? null,
                                'imagen'       => $imagePath,
                                'precio_extra' => $valorData['precio_extra'] ?? 0,
                                'orden'        => $vIndex,
                            ]);

                            $valorIdMap["{$gIndex}_{$vIndex}"] = $valor->id_valor;
                        }
                    }
                }
            }

            // 3. Create Variants
            if (!empty($data['variantes'])) {
                foreach ($data['variantes'] as $variantData) {
                    $variante = $product->variantes()->create([
                        'precio' => $variantData['precio'],
                        'stock'  => $variantData['stock'],
                        'sku'    => $variantData['sku'] ?? null,
                    ]);

                    // Link options
                    if (!empty($variantData['valores'])) { // Array of "GroupIndex_ValueIndex"
                        $idsToSync = [];
                        foreach ($variantData['valores'] as $valKey) {
                            if (isset($valorIdMap[$valKey])) {
                                $idsToSync[] = $valorIdMap[$valKey];
                            }
                        }
                        $variante->valores()->sync($idsToSync);
                    }
                }
            }

            // 4. Upload gallery images
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $index => $file) {
                    $path = $file->store("products/{$product->id_producto}/gallery", 'public');

                    $product->imagenes()->create([
                        'ruta'  => $path,
                        'orden' => $index,
                    ]);
                }
            }

            return $product;
        });
    }

    /**
     * Update an existing product. 
     * For simplicity, this rebuilds the variants if provided.
     */
    public function updateProduct(Producto $product, array $data, Request $request): Producto
    {
        return DB::transaction(function () use ($data, $request, $product) {
            // 1. Update product base info
            $product->update([
                'nombre'       => $data['nombre'],
                'descripcion'  => $data['descripcion'] ?? null,
                'id_categoria' => $data['id_categoria'] ?? null,
                'brand'        => $data['brand'] ?? null,
                'precio_base'  => $data['precio_base'] ?? 0,
            ]);

            // Update existing variants (price, stock, sku)
            if (!empty($data['variantes_existentes'])) {
                foreach ($data['variantes_existentes'] as $variantData) {
                    if (isset($variantData['id_variante'])) {
                        $variant = Variante::find($variantData['id_variante']);
                        if ($variant && $variant->id_producto === $product->id_producto) {
                            $variant->update([
                                'precio' => $variantData['precio'],
                                'stock'  => $variantData['stock'],
                                'sku'    => $variantData['sku'] ?? null,
                            ]);
                        }
                    }
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

            return $product;
        });
    }

    /**
     * Soft-delete the product and its variants.
     */
    public function deleteProduct(Producto $product): void
    {
        DB::transaction(function () use ($product) {
            $product->variantes()->delete();
            $product->delete();
        });
    }
}
