<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            // Variants
            'variantes'                  => ['required', 'array', 'min:1'],
            'variantes.*.color'          => ['required', 'string', 'max:50'],
            'variantes.*.almacenamiento' => ['required', 'string', 'max:50'],
            'variantes.*.precio'         => ['required', 'numeric', 'min:0'],
            'variantes.*.stock'          => ['required', 'integer', 'min:0'],
            'variantes.*.sku'            => ['nullable', 'string', 'max:50'],
            'variantes.*.imagen'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Gallery images
            'imagenes'   => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
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
            'brand.max'                        => 'La marca no puede superar los 100 caracteres.',
            'variantes.required'               => 'Debe añadir al menos una variante.',
            'variantes.min'                    => 'Debe añadir al menos una variante.',
            'variantes.*.color.required'       => 'El color es obligatorio para cada variante.',
            'variantes.*.almacenamiento.required' => 'El almacenamiento es obligatorio para cada variante.',
            'variantes.*.precio.required'      => 'El precio es obligatorio para cada variante.',
            'variantes.*.precio.numeric'       => 'El precio debe ser un número.',
            'variantes.*.precio.min'           => 'El precio no puede ser negativo.',
            'variantes.*.stock.required'       => 'El stock es obligatorio para cada variante.',
            'variantes.*.stock.integer'        => 'El stock debe ser un número entero.',
            'variantes.*.stock.min'            => 'El stock no puede ser negativo.',
            'variantes.*.imagen.image'         => 'El archivo de variante debe ser una imagen.',
            'variantes.*.imagen.max'           => 'La imagen de variante no puede superar los 2 MB.',
            'imagenes.*.image'                 => 'Cada archivo de galería debe ser una imagen.',
            'imagenes.*.max'                   => 'Las imágenes de galería no pueden superar los 4 MB.',
        ];
    }
}
