@extends('layouts.admin')

@section('title', 'Estadísticas de Ventas - Synapse Admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Estadísticas</h1>
        <p class="text-gray-500 mt-1">Resumen de ventas y rendimiento por período.</p>
    </div>

    <!-- Date range filter -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.stats.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Período
            </div>
            <input type="date" name="from" value="{{ $from->toDateString() }}"
                class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            <span class="text-gray-400 self-center hidden sm:block">—</span>
            <input type="date" name="to" value="{{ $to->toDateString() }}"
                class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
                Aplicar
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-3">Solo se incluyen pedidos confirmados (pagado, enviado, entregado).</p>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl shadow-lg p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Ingresos totales</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['totalRevenue'], 2, ',', '.') }} €</p>
        </div>
        <div class="bg-white rounded-3xl shadow-lg p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Pedidos confirmados</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['totalOrders']) }}</p>
        </div>
        <div class="bg-white rounded-3xl shadow-lg p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Ticket medio</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['avgTicket'], 2, ',', '.') }} €</p>
        </div>
    </div>

    <!-- Revenue chart -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Ingresos por día</h2>
        <div class="relative h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top products + status breakdown side by side -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Top products (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Productos más vendidos</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-6">#</th>
                            <th class="py-3 px-6">Producto</th>
                            <th class="py-3 px-6 text-center">Unidades</th>
                            <th class="py-3 px-6 text-right">Ingresos</th>
                            <th class="py-3 px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topProducts as $i => $product)
                            @php
                                $maxRevenue = $topProducts[0]->revenue ?? 1;
                                $pct = $maxRevenue > 0 ? round(($product->revenue / $maxRevenue) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 text-gray-400 font-medium">{{ $i + 1 }}</td>
                                <td class="py-4 px-6 font-medium text-gray-900">{{ $product->nombre }}</td>
                                <td class="py-4 px-6 text-center text-gray-700">{{ number_format($product->units) }}</td>
                                <td class="py-4 px-6 text-right font-semibold text-gray-900">
                                    {{ number_format($product->revenue, 2, ',', '.') }} €
                                </td>
                                <td class="py-4 px-6 w-28">
                                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-2 bg-[#004689] rounded-full" style="width:{{ $pct }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400">Sin datos en este período.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status breakdown -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Pedidos por estado</h2>
            @if(!empty($byStatus))
                <div class="relative flex-1 flex items-center justify-center mb-6">
                    <canvas id="statusChart" class="max-h-52"></canvas>
                </div>
                <ul class="space-y-2 text-sm">
                    @php
                        $statusColors = [
                            'pendiente' => 'bg-orange-400',
                            'pagado'    => 'bg-blue-400',
                            'enviado'   => 'bg-purple-400',
                            'entregado' => 'bg-green-400',
                        ];
                    @endphp
                    @foreach($byStatus as $estado => $count)
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full {{ $statusColors[$estado] ?? 'bg-gray-400' }}"></span>
                                <span class="capitalize text-gray-700">{{ $estado }}</span>
                            </span>
                            <span class="font-semibold text-gray-900">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-400 text-sm text-center mt-4">Sin datos en este período.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
(function () {
    // Revenue line chart
    const revenueData = @json($revenueByDay);
    const labels = revenueData.map(d => d.day);
    const values = revenueData.map(d => d.total);

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Ingresos (€)',
                data: values,
                borderColor: '#004689',
                backgroundColor: 'rgba(0,70,137,0.08)',
                borderWidth: 2,
                pointRadius: labels.length > 60 ? 0 : 3,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.3,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: { maxTicksLimit: 12, font: { size: 11 } },
                    grid: { display: false },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 11 },
                        callback: v => v.toLocaleString('es-ES') + ' €',
                    },
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y.toLocaleString('es-ES', { minimumFractionDigits: 2 }) + ' €',
                    },
                },
            },
        },
    });

    // Status doughnut chart
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        const statusData = @json($byStatus);
        const statusLabels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
        const statusValues = Object.values(statusData);
        const palette = {
            pendiente: '#fb923c',
            pagado:    '#60a5fa',
            enviado:   '#a78bfa',
            entregado: '#4ade80',
        };
        const bgColors = Object.keys(statusData).map(s => palette[s] ?? '#9ca3af');

        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{ data: statusValues, backgroundColor: bgColors, borderWidth: 0 }],
            },
            options: {
                cutout: '65%',
                plugins: { legend: { display: false } },
            },
        });
    }
})();
</script>
@endpush