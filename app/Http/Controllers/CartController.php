<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\RemoveCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Carrito;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $carrito = Carrito::with(['variante.producto', 'variante.valores'])->where('id_usuario', $user->id)->get();
        return view('cart.index', compact('carrito'));
    }

    public function add(AddToCartRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $cantidad = $data['cantidad'] ?? 1;

        $cartItem = Carrito::where('id_usuario', $user->id)
                           ->where('id_variante', $data['id_variante'])
                           ->first();

        if ($cartItem) {
            $cartItem->cantidad += $cantidad;
            $cartItem->save();
        } else {
            Carrito::create([
                'id_usuario'  => $user->id,
                'id_variante' => $data['id_variante'],
                'cantidad'    => $cantidad,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Producto añadido al carrito');
    }

    public function remove(RemoveCartItemRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        Carrito::where('id_usuario', $user->id)
               ->where('id_carrito', $data['id_carrito'])
               ->delete();

        return redirect()->route('cart.index')->with('success', 'Producto eliminado');
    }

    public function update(UpdateCartItemRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        $cartItem = Carrito::where('id_usuario', $user->id)
                           ->where('id_carrito', $data['id_carrito'])
                           ->first();

        if ($cartItem) {
            if ($data['action'] === 'increment') {
                $cartItem->cantidad++;
                $cartItem->save();
            } elseif ($data['action'] === 'decrement') {
                if ($cartItem->cantidad > 1) {
                    $cartItem->cantidad--;
                    $cartItem->save();
                } else {
                    $cartItem->delete();
                    return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito');
                }
            }
        }

        return redirect()->route('cart.index');
    }
}
