<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SalesStatsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    public function __construct(protected SalesStatsService $statsService) {}

    public function index(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : now()->subDays(29)->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : now()->endOfDay();

        $report = $this->statsService->report($from, $to);

        return view('admin.stats.index', [
            'from'        => $from,
            'to'          => $to,
            'kpis'        => $report['kpis'],
            'revenueByDay' => $report['revenueByDay'],
            'topProducts'  => $report['topProducts'],
            'byStatus'     => $report['byStatus'],
        ]);
    }
}