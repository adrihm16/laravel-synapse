@extends('layouts.admin')

@section('title', 'Gestión de Pedidos - Synapse Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Pedidos</h1>
            <p class="text-gray-500 mt-1">Consulta y gestiona todos los pedidos de la tienda.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <x-icon name="check-circle" class="w-5 h-5 text-green-500 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <x-icon name="exclamation-circle" class="w-5 h-5 text-red-500 shrink-0" />
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por referencia o cliente..."
                    class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            </div>
            <select name="estado" class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition min-w-[160px]">
                <option value="">Todos los estados</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('estado') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}"
                class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            <input type="date" name="to" value="{{ request('to') }}"
                class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
                Filtrar
            </button>
            @if(request('search') || request('estado') || request('from') || request('to'))
                <a href="{{ route('admin.orders.index') }}" class="rounded-full border-2 border-gray-200 text-gray-600 hover:border-gray-400 font-semibold py-3 px-6 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Referencia</th>
                        <th class="py-4 px-6">Cliente</th>
                        <th class="py-4 px-6">Fecha</th>
                        <th class="py-4 px-6">Artículos</th>
                        <th class="py-4 px-6">Total</th>
                        <th class="py-4 px-6">Estado</th>
                        <th class="py-4 px-6 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($orders as $pedido)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-mono font-medium text-gray-900">
                                {{ $pedido->referencia }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">{{ $pedido->user->name ?? $pedido->nombre_envio }}</div>
                                <div class="text-xs text-gray-400">{{ $pedido->user->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                {{ $pedido->detalles_count }} ud{{ $pedido->detalles_count !== 1 ? 's' : '' }}.
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900">
                                {{ number_format($pedido->total, 2, ',', '.') }} €
                            </td>
                            <td class="py-4 px-6">
                                <x-admin.status-badge :status="$pedido->estado" />
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.orders.show', $pedido) }}"
                                    class="p-2 rounded-xl text-gray-400 hover:text-[#004689] hover:bg-blue-50 transition inline-flex" title="Ver detalle">
                                    <x-icon name="eye" class="w-5 h-5" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" />
                                </svg>
                                <p class="text-gray-500 font-medium">No se encontraron pedidos.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->withQueryString()->links('vendor.pagination.synapse') }}
            </div>
        @endif
    </div>
</div>
@endsection