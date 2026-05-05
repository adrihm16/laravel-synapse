<?php

namespace App\Http\Controllers;

use App\Models\Carrito;

class CheckoutController extends Controller
{
    public function index()
    {
        $carrito = Carrito::where('id_usuario', auth()->id())->get();

        if ($carrito->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío. Añade productos antes de continuar.');
        }

        return view('checkout.index');
    }
}
