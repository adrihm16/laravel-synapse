<?php

namespace App\Services;

use App\Models\Pedido;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class SalesStatsService
{
    private const CONFIRMED_STATUSES = ['pagado', 'enviado', 'entregado'];

    public function report(Carbon $from, Carbon $to): array
    {
        return [
            'kpis'        => $this->kpis($from, $to),
            'revenueByDay' => $this->revenueByDay($from, $to),
            'topProducts'  => $this->topProducts($from, $to),
            'byStatus'     => $this->byStatus($from, $to),
        ];
    }

    private function kpis(Carbon $from, Carbon $to): array
    {
        $base = Pedido::whereIn('estado', self::CONFIRMED_STATUSES)
            ->whereBetween('fecha', [$from, $to]);

        $totalRevenue = (float) (clone $base)->sum('total');
        $totalOrders  = (clone $base)->count();
        $avgTicket    = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return compact('totalRevenue', 'totalOrders', 'avgTicket');
    }

    private function revenueByDay(Carbon $from, Carbon $to): array
    {
        $rows = Pedido::selectRaw('DATE(fecha) as day, SUM(total) as total')
            ->whereIn('estado', self::CONFIRMED_STATUSES)
            ->whereBetween('fecha', [$from, $to])
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        // Fill gaps so the chart x-axis is continuous
        $result = [];
        foreach (CarbonPeriod::create($from->toDateString(), $to->toDateString()) as $date) {
            $key = $date->toDateString();
            $result[] = ['day' => $key, 'total' => (float) ($rows[$key] ?? 0)];
        }

        return $result;
    }

    private function topProducts(Carbon $from, Carbon $to): array
    {
        return DB::table('detalle_pedido as dp')
            ->join('pedidos as p', 'p.id_pedido', '=', 'dp.id_pedido')
            ->join('variantes as v', 'v.id_variante', '=', 'dp.id_variante')
            ->join('productos as pr', 'pr.id_producto', '=', 'v.id_producto')
            ->whereIn('p.estado', self::CONFIRMED_STATUSES)
            ->whereBetween('p.fecha', [$from, $to])
            ->groupBy('pr.id_producto', 'pr.nombre')
            ->selectRaw('pr.id_producto, pr.nombre, SUM(dp.cantidad) as units, SUM(dp.cantidad * dp.precio_unitario) as revenue')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function byStatus(Carbon $from, Carbon $to): array
    {
        return Pedido::selectRaw('estado, COUNT(*) as total')
            ->whereBetween('fecha', [$from, $to])
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();
    }
}