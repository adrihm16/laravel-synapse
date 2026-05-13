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

            // Variants
            'variantes'                  => ['nullable', 'array'],
            'variantes.*.precio'         => ['required', 'numeric', 'min:0'],
            'variantes.*.stock'          => ['required', 'integer', 'min:0'],
            'variantes.*.sku'            => ['nullable', 'string', 'max:50'],
            'variantes.*.valores'        => ['nullable', 'array'], // e.g. ["0_0", "1_0"]
            'variantes.*.valores.*'      => ['string'],

            // Gallery images
            'imagenes'   => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Images to delete
            'eliminar_imagenes'   => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['integer', 'exists:imagen_productos,id_imagen'],
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
        ];
    }
}
