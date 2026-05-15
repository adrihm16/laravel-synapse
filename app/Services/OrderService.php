<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function changeStatus(Pedido $pedido, string $estado): Pedido
    {
        DB::transaction(function () use ($pedido, $estado) {
            $pedido->estado = $estado;
            $pedido->save();
        });

        return $pedido;
    }
}