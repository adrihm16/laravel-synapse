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

    <!-- Banner Principal -->
    @if($heroBanner)
    <div class="bg-white rounded-[2rem] shadow-xl p-8 mt-12 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl text-gray-900 tracking-tight">Banner Principal</h2>
                <p class="text-sm text-gray-500 mt-1">Imagen mostrada en la portada de la tienda.</p>
            </div>
            <span class="text-xs text-gray-400 text-right leading-relaxed">
                Escritorio: 1918×455 px<br>
                Móvil: 1696×2528 px
            </span>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl text-sm font-medium mb-6 flex items-center gap-3"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
                <x-icon name="check-circle" class="w-5 h-5 text-green-500 shrink-0" />
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm font-medium mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <x-icon name="exclamation-triangle" class="w-5 h-5 text-red-500" />
                    <span class="font-bold">Por favor, corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 ml-7">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.hero-banner.update', $heroBanner) }}"
              enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Escritorio (1918×455)</label>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden mb-3">
                        <img src="{{ $heroBanner->imagen_desktop_url }}" alt="Banner escritorio actual"
                             class="w-full h-32 object-cover" />
                    </div>
                    <input type="file" name="imagen_desktop" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#004689] file:text-white hover:file:bg-[#002244] transition cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">Dejar vacío para conservar la actual. Máx. 4 MB.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Móvil (1696×2528)</label>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden mb-3 flex justify-center">
                        <img src="{{ $heroBanner->imagen_mobile_url }}" alt="Banner móvil actual"
                             class="h-32 w-auto object-cover" />
                    </div>
                    <input type="file" name="imagen_mobile" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#004689] file:text-white hover:file:bg-[#002244] transition cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">Dejar vacío para conservar la actual. Máx. 6 MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enlace (opcional)</label>
                    <input type="url" name="enlace" value="{{ old('enlace', $heroBanner->enlace) }}"
                           placeholder="https://..."
                           class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                    <p class="text-xs text-gray-400 mt-1">Si se deja vacío, el banner enlaza al catálogo.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título / Alt</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $heroBanner->titulo) }}"
                           maxlength="100" placeholder="Texto alternativo"
                           class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                </div>
            </div>

            <div>
                <input type="hidden" name="activo" value="0">
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="activo" value="1"
                           {{ old('activo', $heroBanner->activo) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-gray-300 text-[#004689] focus:ring-[#004689]/20">
                    <span class="text-sm font-medium text-gray-700">Mostrar el banner en la portada</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-8 transition shadow-md active:scale-95">
                    <x-icon name="check-circle" class="w-5 h-5" />
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
    @endif

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
