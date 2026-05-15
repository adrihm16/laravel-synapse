@extends('layouts.admin')

@section('title', 'Pedido ' . $pedido->referencia . ' - Synapse Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-[#004689] hover:text-[#002244] transition inline-flex items-center gap-1 mb-2">
                <x-icon name="chevron-left" class="w-4 h-4" />
                Volver a pedidos
            </a>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">{{ $pedido->referencia }}</h1>
            <p class="text-gray-500 mt-1">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y \a \l\a\s H:i') }}</p>
        </div>
        <x-admin.status-badge :status="$pedido->estado" />
    </div>

    <!-- Flash -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <x-icon name="check-circle" class="w-5 h-5 text-green-500 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Line items (spans 2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Artículos del pedido</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="py-3 px-6">Producto</th>
                                <th class="py-3 px-6">Opciones</th>
                                <th class="py-3 px-6 text-center">Cant.</th>
                                <th class="py-3 px-6 text-right">P. Unit.</th>
                                <th class="py-3 px-6 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($pedido->detalles as $detalle)
                                @php
                                    $variante   = $detalle->variante;
                                    $producto   = $variante?->producto;
                                    $opciones   = $variante?->valores->map(fn($v) => $v->grupo->nombre . ': ' . $v->valor)->implode(', ');
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ $producto?->nombre ?? '—' }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 text-xs">
                                        {{ $opciones ?: '—' }}
                                    </td>
                                    <td class="py-4 px-6 text-center text-gray-700">{{ $detalle->cantidad }}</td>
                                    <td class="py-4 px-6 text-right text-gray-700">{{ number_format($detalle->precio_unitario, 2, ',', '.') }} €</td>
                                    <td class="py-4 px-6 text-right font-semibold text-gray-900">
                                        {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2, ',', '.') }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="4" class="py-4 px-6 text-right font-bold text-gray-900">Total</td>
                                <td class="py-4 px-6 text-right font-black text-xl text-gray-900">
                                    {{ number_format($pedido->total, 2, ',', '.') }} €
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right column: customer + shipping + status -->
        <div class="space-y-6">
            <!-- Customer -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Cliente</h2>
                @if($pedido->user)
                    <p class="font-medium text-gray-900">{{ $pedido->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $pedido->user->email }}</p>
                @else
                    <p class="text-sm text-gray-500">Usuario eliminado</p>
                @endif
            </div>

            <!-- Shipping -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Dirección de envío</h2>
                <div class="space-y-1 text-sm text-gray-700">
                    <p class="font-medium">{{ $pedido->nombre_envio }}</p>
                    <p>{{ $pedido->direccion }}</p>
                    <p>{{ $pedido->codigo_postal }} {{ $pedido->ciudad }}</p>
                    <p>{{ $pedido->provincia }}</p>
                    @if($pedido->telefono)
                        <p class="text-gray-500 pt-1">Tel: {{ $pedido->telefono }}</p>
                    @endif
                </div>
            </div>

            <!-- Change status -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Cambiar estado</h2>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $pedido) }}">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-3">
                        <select name="estado"
                            class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $pedido->estado === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <p class="text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                            class="w-full rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-4 transition shadow-md active:scale-95">
                            Guardar estado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection