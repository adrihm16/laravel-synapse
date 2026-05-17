<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\HeroBanner;
use App\Models\Pedido;
use App\Models\Variante;

class DashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            'total_productos' => Producto::count(),
            'total_categorias' => Categoria::count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'ingresos_mes' => Pedido::whereMonth('fecha', now()->month)->sum('total'),
            'stock_bajo' => Variante::where('stock', '<', 5)->count(),
        ];

        $latestOrders = Pedido::with('user')
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        $heroBanner = HeroBanner::where('activo', true)
            ->orderBy('orden')
            ->first()
            ?? HeroBanner::orderBy('orden')->first();

        return view('admin.dashboard', compact('kpis', 'latestOrders', 'heroBanner'));
    }
}
