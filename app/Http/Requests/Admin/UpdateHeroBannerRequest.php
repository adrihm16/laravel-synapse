<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'imagen_desktop' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096',
                                  'dimensions:width=1918,height=455'],
            'imagen_mobile'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144',
                                  'dimensions:width=1696,height=2528'],
            'enlace'         => ['nullable', 'url', 'max:255'],
            'titulo'         => ['nullable', 'string', 'max:100'],
            'activo'         => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'imagen_desktop.image'      => 'El archivo de escritorio debe ser una imagen.',
            'imagen_desktop.mimes'      => 'La imagen de escritorio debe estar en formato JPG, PNG o WEBP.',
            'imagen_desktop.max'        => 'La imagen de escritorio no puede superar los 4 MB.',
            'imagen_desktop.dimensions' => 'La imagen de escritorio debe medir exactamente 1918×455 píxeles.',

            'imagen_mobile.image'       => 'El archivo móvil debe ser una imagen.',
            'imagen_mobile.mimes'       => 'La imagen móvil debe estar en formato JPG, PNG o WEBP.',
            'imagen_mobile.max'         => 'La imagen móvil no puede superar los 6 MB.',
            'imagen_mobile.dimensions'  => 'La imagen móvil debe medir exactamente 1696×2528 píxeles.',

            'enlace.url'                => 'El enlace debe ser una URL válida.',
            'enlace.max'                => 'El enlace no puede superar los 255 caracteres.',
            'titulo.max'                => 'El título no puede superar los 100 caracteres.',
        ];
    }
}
