<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Carrito;

class StoreCart extends Component
{
    public function increment($id_carrito)
    {
        $user = auth()->user();
        $cartItem = Carrito::where('id_usuario', $user->id)
                           ->where('id_carrito', $id_carrito)
                           ->first();
        if ($cartItem) {
            $cartItem->cantidad++;
            $cartItem->save();
        }
    }

    public function decrement($id_carrito)
    {
        $user = auth()->user();
        $cartItem = Carrito::where('id_usuario', $user->id)
                           ->where('id_carrito', $id_carrito)
                           ->first();
        if ($cartItem) {
            if ($cartItem->cantidad > 1) {
                $cartItem->cantidad--;
                $cartItem->save();
            } else {
                $cartItem->delete();
                $this->dispatch('notify', message: 'Producto eliminado del carrito', type: 'success');
            }
        }
    }

    public function remove($id_carrito)
    {
        $user = auth()->user();
        Carrito::where('id_usuario', $user->id)
               ->where('id_carrito', $id_carrito)
               ->delete();
        $this->dispatch('notify', message: 'Producto eliminado', type: 'success');
    }

    public function render()
    {
        $user = auth()->user();
        $carrito = Carrito::with([
            'variante.producto.imagenes',
            'variante.valores',
        ])->where('id_usuario', $user->id)->get();
        return view('livewire.store-cart', compact('carrito'));
    }
}
