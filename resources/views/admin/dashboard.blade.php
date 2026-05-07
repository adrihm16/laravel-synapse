@extends('layouts.admin')

@section('title', 'Admin Dashboard - Synapse')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Dashboard General</h1>
        <div class="text-sm text-gray-500">
            Actualizado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- KPIs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        
        <!-- KPI: Total de Productos -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Productos</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_productos']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Activos en catálogo</p>
            </div>
        </div>

        <!-- KPI: Total de Categorías -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Categorías</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_categorias']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Sistemas de clasificación</p>
            </div>
        </div>

        <!-- KPI: Pedidos Pendientes -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendientes</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['pedidos_pendientes']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Pedidos sin enviar</p>
            </div>
        </div>

        <!-- KPI: Ingresos del Mes -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ingresos</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['ingresos_mes'], 2, ',', '.') }} €</p>
                <p class="text-sm text-gray-500 mt-1">Ventas este mes</p>
            </div>
        </div>

        <!-- KPI: Stock Bajo -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300 border border-transparent {{ $kpis['stock_bajo'] > 0 ? 'border-red-100 shadow-red-100/50' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 {{ $kpis['stock_bajo'] > 0 ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400' }} rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Alertas</span>
            </div>
            <div>
                <p class="text-3xl font-bold {{ $kpis['stock_bajo'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($kpis['stock_bajo']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Variantes con poco stock</p>
            </div>
        </div>
    </div>

    <!-- Últimos Pedidos -->
    <div class="bg-white rounded-3xl shadow-lg p-6 mt-8 overflow-hidden">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Últimos Pedidos</h2>
            <a href="#" class="text-sm font-semibold text-[#004689] hover:underline">Ver todos &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-4">Ref.</th>
                        <th class="py-4 px-4">Cliente</th>
                        <th class="py-4 px-4">Fecha</th>
                        <th class="py-4 px-4">Estado</th>
                        <th class="py-4 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($latestOrders as $pedido)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-4 font-medium text-gray-900">{{ $pedido->referencia ?? 'SYN-' . str_pad($pedido->id_pedido, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $pedido->user->name ?? $pedido->nombre_envio }}</div>
                                <div class="text-xs text-gray-500">{{ $pedido->user->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-4 text-gray-600">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</td>
                            <td class="py-4 px-4">
                                @php
                                    $statusClasses = [
                                        'pendiente' => 'bg-orange-100 text-orange-800',
                                        'pagado' => 'bg-blue-100 text-blue-800',
                                        'enviado' => 'bg-purple-100 text-purple-800',
                                        'entregado' => 'bg-green-100 text-green-800',
                                    ];
                                    $class = $statusClasses[$pedido->estado] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $class }}">
                                    {{ $pedido->estado }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-gray-900">{{ number_format($pedido->total, 2, ',', '.') }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                No hay pedidos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
