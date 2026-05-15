<?php

namespace App\Services;

use App\Models\ImagenProducto;
use App\Models\Producto;
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

            // Delete specified existing variants
            if (!empty($data['eliminar_variantes'])) {
                $toDelete = Variante::whereIn('id_variante', $data['eliminar_variantes'])
                    ->where('id_producto', $product->id_producto)
                    ->get();
                foreach ($toDelete as $v) {
                    $v->valores()->detach();
                    $v->delete();
                }
            }

            // Delete specified option values and cascade to any variants still linked to them
            if (!empty($data['eliminar_valores'])) {
                $valores = \App\Models\ValorOpcionProducto::whereIn('id_valor', $data['eliminar_valores'])
                    ->whereHas('grupo', fn($q) => $q->where('id_producto', $product->id_producto))
                    ->get();
                foreach ($valores as $valor) {
                    $linkedIds = DB::table('variante_valores')
                        ->where('id_valor', $valor->id_valor)
                        ->pluck('id_variante');
                    foreach ($linkedIds as $varId) {
                        $v = Variante::find($varId);
                        if ($v) {
                            $v->valores()->detach();
                            $v->delete();
                        }
                    }
                    $valor->delete();
                }
            }

            // Update existing variants (price, stock, sku only)
            if (!empty($data['variantes_existentes'])) {
                foreach ($data['variantes_existentes'] as $variantData) {
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

            // Persist new option values into existing groups and build a ref → id_valor map
            // so new variants can reference them via temp keys like "nv_{groupId}_{index}".
            $newValueRefMap = [];
            if (!empty($data['valores_nuevos'])) {
                foreach ($data['valores_nuevos'] as $groupId => $newValues) {
                    $grupo = $product->gruposOpciones()->find($groupId);
                    if (!$grupo) continue;
                    $nextOrden = $grupo->valores()->max('orden') ?? -1;
                    foreach ($newValues as $index => $valorData) {
                        $nextOrden++;
                        $valor = $grupo->valores()->create([
                            'nombre'       => $valorData['nombre'],
                            'hex_code'     => !empty($valorData['hex_code']) ? $valorData['hex_code'] : null,
                            'precio_extra' => $valorData['precio_extra'] ?? 0,
                            'orden'        => $nextOrden,
                        ]);
                        $newValueRefMap["nv_{$groupId}_{$index}"] = $valor->id_valor;
                    }
                }
            }

            // Create new variants, resolving both existing value IDs and new value temp refs
            if (!empty($data['variantes_nuevas'])) {
                foreach ($data['variantes_nuevas'] as $newVariant) {
                    $variante = $product->variantes()->create([
                        'precio' => $newVariant['precio'],
                        'stock'  => $newVariant['stock'],
                        'sku'    => $newVariant['sku'] ?? null,
                    ]);

                    $valuesToAttach = [];

                    foreach ($newVariant['valores_existentes'] ?? [] as $id) {
                        $valuesToAttach[] = (int) $id;
                    }

                    foreach ($newVariant['valores_nuevos'] ?? [] as $ref) {
                        if (isset($newValueRefMap[$ref])) {
                            $valuesToAttach[] = $newValueRefMap[$ref];
                        }
                    }

                    if (!empty($valuesToAttach)) {
                        $variante->valores()->attach($valuesToAttach);
                    }
                }
            }

            // 5. Delete removed gallery images (global + per-color)
            if (!empty($data['eliminar_imagenes'])) {
                $imagesToDelete = ImagenProducto::whereIn('id_imagen', $data['eliminar_imagenes'])
                    ->where('id_producto', $product->id_producto)
                    ->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->ruta);
                    $img->delete();
                }
            }

            // 6. Upload new global gallery images
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

            // 7. Upload new per-color gallery images
            // Input name: galeria_color[{id_valor}][] (multiple files per color)
            if ($request->hasFile('galeria_color')) {
                foreach ($request->file('galeria_color') as $idValor => $files) {
                    $maxOrder = ImagenProducto::where('id_producto', $product->id_producto)
                        ->where('id_valor', $idValor)
                        ->max('orden') ?? -1;

                    foreach ($files as $file) {
                        $maxOrder++;
                        $path = $file->store("products/{$product->id_producto}/colors/{$idValor}", 'public');

                        ImagenProducto::create([
                            'id_producto' => $product->id_producto,
                            'id_valor'    => $idValor,
                            'ruta'        => $path,
                            'orden'       => $maxOrder,
                        ]);
                    }
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
