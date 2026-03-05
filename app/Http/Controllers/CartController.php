<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $carrito = Carrito::with('variante.producto')->where('id_usuario', $user->id)->get();
        return view('cart.index', compact('carrito'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id_variante' => 'required|exists:variantes_producto,id_variante',
            'cantidad' => 'integer|min:1',
        ]);

        $user = auth()->user();

        $cartItem = Carrito::where('id_usuario', $user->id)
                           ->where('id_variante', $request->id_variante)
                           ->first();

        if ($cartItem) {
            $cartItem->cantidad += $request->input('cantidad', 1);
            $cartItem->save();
        } else {
            Carrito::create([
                'id_usuario' => $user->id,
                'id_variante' => $request->id_variante,
                'cantidad' => $request->input('cantidad', 1),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Producto añadido al carrito');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id_carrito' => 'required|exists:carrito,id_carrito',
        ]);

        $user = auth()->user();
        Carrito::where('id_usuario', $user->id)
               ->where('id_carrito', $request->id_carrito)
               ->delete();

        return redirect()->route('cart.index')->with('success', 'Producto eliminado');
    }
}
