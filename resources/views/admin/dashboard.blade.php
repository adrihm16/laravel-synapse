@extends('layouts.admin')

@section('title', 'Admin Dashboard - Synapse')

@section('content')
<div class="space-y-10">
    <!-- KPIs Banner -->
    @php
        $stats = [
            [
                'title' => 'Productos',
                'value' => number_format($kpis['total_productos']),
                'description' => 'Activos en catálogo',
                'icon' => 'box',
            ],
            [
                'title' => 'Categorías',
                'value' => number_format($kpis['total_categorias']),
                'description' => 'Estructura comercial',
                'icon' => 'tag',
            ],
            [
                'title' => 'Pendientes',
                'value' => number_format($kpis['pedidos_pendientes']),
                'description' => 'Sin gestionar',
                'icon' => 'search',
            ],
            [
                'title' => 'Ingresos',
                'value' => number_format($kpis['ingresos_mes'], 2, ',', '.') . ' €',
                'description' => 'Ventas del mes',
                'icon' => 'check-circle',
            ],
            [
                'title' => 'Alertas',
                'value' => number_format($kpis['stock_bajo']),
                'description' => 'Bajo inventario',
                'icon' => 'exclamation-triangle',
                'alert' => $kpis['stock_bajo'] > 0,
            ],
        ];
    @endphp

    <x-admin.kpi-banner :stats="$stats" />

    <!-- Últimos Pedidos -->
    <div class="bg-white rounded-[2rem] shadow-xl p-8 mt-12 overflow-hidden border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl text-gray-900 tracking-tight">Últimos Pedidos</h2>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-sm text-[#004689] hover:text-[#002244] transition-colors group">
                Ver historial completo
                <x-icon name="chevron-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">
                        <th class="py-4 px-4">Referencia</th>
                        <th class="py-4 px-4">Cliente</th>
                        <th class="py-4 px-4">Fecha / Hora</th>
                        <th class="py-4 px-4">Estado</th>
                        <th class="py-4 px-4 text-right">Monto Total</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($latestOrders as $pedido)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-5 px-4 text-gray-900">{{ $pedido->referencia ?? 'SYN-' . str_pad($pedido->id_pedido, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-4">
                                <div class="font-bold text-gray-900">{{ $pedido->user->name ?? $pedido->nombre_envio }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $pedido->user->email ?? '' }}</div>
                            </td>
                            <td class="py-5 px-4 text-gray-500 font-medium">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</td>
                            <td class="py-5 px-4">
                                <x-admin.status-badge :status="$pedido->estado" />
                            </td>
                            <td class="py-5 px-4 text-right font-black text-gray-900 text-lg">{{ number_format($pedido->total, 2, ',', '.') }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 italic">
                                No se registran movimientos recientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
