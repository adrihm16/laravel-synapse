<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'id_variante' => ['required', 'exists:variantes,id_variante'],
            'cantidad'    => ['nullable', 'integer', 'min:1'],
        ];
    }
}
