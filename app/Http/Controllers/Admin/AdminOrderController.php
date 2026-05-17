<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Pedido;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function index(Request $request)
    {
        $orders = Pedido::with('user')
            ->withCount('detalles')
            ->filter($request->only(['search', 'estado', 'from', 'to']))
            ->orderByDesc('fecha')
            ->paginate(15);

        $statuses = OrderStatus::values();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load([
            'user',
            'detalles.variante.producto',
            'detalles.variante.valores.grupo',
        ]);

        $statuses = OrderStatus::manuallyAssignable();

        return view('admin.orders.show', compact('pedido', 'statuses'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Pedido $pedido)
    {
        $this->orderService->changeStatus($pedido, $request->validated('estado'));

        return redirect()
            ->route('admin.orders.show', $pedido)
            ->with('success', 'Estado del pedido actualizado correctamente.');
    }
}