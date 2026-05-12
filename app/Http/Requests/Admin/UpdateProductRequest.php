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

            // Existing variants (update)
            'variantes_existentes'                  => ['nullable', 'array'],
            'variantes_existentes.*.id_variante'    => ['required', 'exists:variantes_producto,id_variante'],
            'variantes_existentes.*.color'          => ['required', 'string', 'max:50'],
            'variantes_existentes.*.almacenamiento' => ['required', 'string', 'max:50'],
            'variantes_existentes.*.precio'         => ['required', 'numeric', 'min:0'],
            'variantes_existentes.*.stock'          => ['required', 'integer', 'min:0'],
            'variantes_existentes.*.sku'            => ['nullable', 'string', 'max:50'],
            'variantes_existentes.*.imagen'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // New variants (create)
            'variantes_nuevas'                  => ['nullable', 'array'],
            'variantes_nuevas.*.color'          => ['required', 'string', 'max:50'],
            'variantes_nuevas.*.almacenamiento' => ['required', 'string', 'max:50'],
            'variantes_nuevas.*.precio'         => ['required', 'numeric', 'min:0'],
            'variantes_nuevas.*.stock'          => ['required', 'integer', 'min:0'],
            'variantes_nuevas.*.sku'            => ['nullable', 'string', 'max:50'],
            'variantes_nuevas.*.imagen'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Gallery images
            'imagenes'   => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Images to delete
            'eliminar_imagenes'   => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['integer', 'exists:imagen_productos,id_imagen'],

            // Variants to delete
            'eliminar_variantes'   => ['nullable', 'array'],
            'eliminar_variantes.*' => ['integer', 'exists:variantes_producto,id_variante'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nombre.required'                              => 'El nombre del producto es obligatorio.',
            'nombre.max'                                   => 'El nombre no puede superar los 150 caracteres.',
            'id_categoria.exists'                          => 'La categoría seleccionada no existe.',
            'variantes_existentes.*.color.required'        => 'El color es obligatorio para cada variante.',
            'variantes_existentes.*.almacenamiento.required' => 'El almacenamiento es obligatorio para cada variante.',
            'variantes_existentes.*.precio.required'       => 'El precio es obligatorio para cada variante.',
            'variantes_existentes.*.precio.min'            => 'El precio no puede ser negativo.',
            'variantes_existentes.*.stock.required'        => 'El stock es obligatorio para cada variante.',
            'variantes_existentes.*.stock.min'             => 'El stock no puede ser negativo.',
            'variantes_nuevas.*.color.required'            => 'El color es obligatorio para cada variante nueva.',
            'variantes_nuevas.*.almacenamiento.required'   => 'El almacenamiento es obligatorio para cada variante nueva.',
            'variantes_nuevas.*.precio.required'           => 'El precio es obligatorio para cada variante nueva.',
            'variantes_nuevas.*.stock.required'            => 'El stock es obligatorio para cada variante nueva.',
            'imagenes.*.image'                             => 'Cada archivo de galería debe ser una imagen.',
            'imagenes.*.max'                               => 'Las imágenes de galería no pueden superar los 4 MB.',
        ];
    }
}
