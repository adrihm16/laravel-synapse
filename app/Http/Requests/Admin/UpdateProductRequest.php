<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],
            'id_categoria' => ['nullable', 'exists:categorias,id_categoria'],
            'brand'        => ['nullable', 'string', 'max:100'],
            'precio_base'  => ['nullable', 'numeric', 'min:0'],
            'destacado'    => ['nullable', 'boolean'],

            // Groups
            'grupos'                  => ['nullable', 'array'],
            'grupos.*.nombre'         => ['required_with:grupos', 'string', 'max:100'],
            'grupos.*.tipo'           => ['nullable', 'in:texto,color,imagen'],
            
            // Values within groups
            'grupos.*.valores'                => ['nullable', 'array'],
            'grupos.*.valores.*.nombre'       => ['required_with:grupos.*.valores', 'string', 'max:100'],
            'grupos.*.valores.*.hex_code'     => ['nullable', 'string', 'max:20'],
            'grupos.*.valores.*.precio_extra' => ['nullable', 'numeric', 'min:0'],
            'grupos.*.valores.*.imagen'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Existing variants (price/stock/sku updates only)
            'variantes_existentes'                  => ['nullable', 'array'],
            'variantes_existentes.*.id_variante'    => ['required_with:variantes_existentes', 'integer', 'exists:variantes,id_variante'],
            'variantes_existentes.*.precio'         => ['required_with:variantes_existentes', 'numeric', 'min:0'],
            'variantes_existentes.*.stock'          => ['required_with:variantes_existentes', 'integer', 'min:0'],
            'variantes_existentes.*.sku'            => ['nullable', 'string', 'max:50'],

            // New variants to add (may reference existing value IDs or temp refs for new values)
            'variantes_nuevas'                          => ['nullable', 'array'],
            'variantes_nuevas.*.precio'                 => ['required_with:variantes_nuevas', 'numeric', 'min:0'],
            'variantes_nuevas.*.stock'                  => ['required_with:variantes_nuevas', 'integer', 'min:0'],
            'variantes_nuevas.*.sku'                    => ['nullable', 'string', 'max:50'],
            'variantes_nuevas.*.valores_existentes'     => ['nullable', 'array'],
            'variantes_nuevas.*.valores_existentes.*'   => ['integer', 'exists:valores_opcion_producto,id_valor'],
            'variantes_nuevas.*.valores_nuevos'         => ['nullable', 'array'],
            'variantes_nuevas.*.valores_nuevos.*'       => ['nullable', 'string', 'max:50'],

            // New option values to persist into existing groups
            'valores_nuevos'                    => ['nullable', 'array'],
            'valores_nuevos.*'                  => ['nullable', 'array'],
            'valores_nuevos.*.*'                => ['nullable', 'array'],
            'valores_nuevos.*.*.nombre'         => ['required_with:valores_nuevos.*', 'string', 'max:100'],
            'valores_nuevos.*.*.hex_code'       => ['nullable', 'string', 'max:20'],
            'valores_nuevos.*.*.precio_extra'   => ['nullable', 'numeric', 'min:0'],

            // Gallery images
            'imagenes'   => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Per-color gallery images
            'galeria_color'       => ['nullable', 'array'],
            'galeria_color.*'     => ['nullable', 'array'],
            'galeria_color.*.*'   => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Images to delete (global + per-color share same table)
            'eliminar_imagenes'   => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['integer', 'exists:imagen_productos,id_imagen'],

            // Variants and option values to delete
            'eliminar_variantes'   => ['nullable', 'array'],
            'eliminar_variantes.*' => ['integer', 'exists:variantes,id_variante'],
            'eliminar_valores'     => ['nullable', 'array'],
            'eliminar_valores.*'   => ['integer', 'exists:valores_opcion_producto,id_valor'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nombre.required'                  => 'El nombre del producto es obligatorio.',
            'nombre.max'                       => 'El nombre no puede superar los 150 caracteres.',
            'id_categoria.exists'              => 'La categoría seleccionada no existe.',
            'grupos.*.nombre.required_with'    => 'El nombre del grupo es obligatorio.',
            'grupos.*.valores.*.nombre.required_with' => 'El nombre del valor es obligatorio.',
            'variantes.*.precio.required'      => 'El precio es obligatorio para cada variante.',
            'variantes.*.precio.numeric'       => 'El precio debe ser un número.',
            'variantes.*.precio.min'           => 'El precio no puede ser negativo.',
            'variantes.*.stock.required'       => 'El stock es obligatorio para cada variante.',
            'variantes.*.stock.integer'        => 'El stock debe ser un número entero.',
            'variantes.*.stock.min'            => 'El stock no puede ser negativo.',
            'grupos.*.valores.*.imagen.image'  => 'El archivo debe ser una imagen.',
            'grupos.*.valores.*.imagen.max'    => 'La imagen no puede superar los 2 MB.',
            'imagenes.*.image'                 => 'Cada archivo de galería debe ser una imagen.',
            'imagenes.*.max'                   => 'Las imágenes de galería no pueden superar los 4 MB.',
            'galeria_color.*.*.image'          => 'Cada imagen de color debe ser una imagen válida.',
            'galeria_color.*.*.max'            => 'Las imágenes de color no pueden superar los 4 MB.',
        ];
    }
}
