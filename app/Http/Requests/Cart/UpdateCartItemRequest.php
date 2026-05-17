<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'id_carrito' => ['required', 'exists:carrito,id_carrito'],
            'action'     => ['required', 'in:increment,decrement'],
        ];
    }
}
