<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        $totalRequests = DB::table('ai_usage_logs')->count();

        $totalTokens = DB::table('ai_usage_logs')->sum('total_tokens');

        $activeCustomers = DB::table('ai_usage_logs')
            ->whereNotNull('ip_address')
            ->distinct('ip_address')
            ->count('ip_address');

        $blocked = DB::table('ai_usage_logs')
            ->where('is_blocked', true)
            ->count();

        $conversionRate = $totalRequests > 0
            ? round((($totalRequests - $blocked) / $totalRequests) * 100, 2)
            : 0;

        return response()->json([
            'total_orders' => $totalRequests,
            'total_revenue' => round($totalTokens / 1000, 1),
            'active_customers' => round($activeCustomers / 1000, 1),
            'conversion_rate' => $conversionRate,
        ]);
    }

    public function aiAnalyticsSummary()
    {
        $now = Carbon::now();
        $startThisPeriod = $now->copy()->subDays(30);
        $startPrevPeriod = $now->copy()->subDays(60);

        $current = DB::table('ai_usage_logs')
            ->where('created_at', '>=', $startThisPeriod)
            ->selectRaw('
            SUM(total_tokens) as total_units,
            COUNT(*) as total_requests,
            COUNT(DISTINCT ip_address) as active_ips,
            SUM(CASE WHEN is_blocked = 1 THEN 1 ELSE 0 END) as blocked
        ')
            ->first();

        $previous = DB::table('ai_usage_logs')
            ->whereBetween('created_at', [$startPrevPeriod, $startThisPeriod])
            ->selectRaw('SUM(total_tokens) as total_units')
            ->first();

        $growth = 0;
        if ($previous->total_units > 0) {
            $growth = round(
                (($current->total_units - $previous->total_units) / $previous->total_units) * 100,
                1
            );
        }

        return response()->json([
            'total_units' => (int) $current->total_units,
            'total_requests' => (int) $current->total_requests,
            'active_ips' => (int) $current->active_ips,
            'success_rate' => $current->total_requests > 0
                ? round((($current->total_requests - $current->blocked) / $current->total_requests) * 100, 2)
                : 100,
            'growth' => $growth,
        ]);
    }

    public function aiTrafficDaily()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfDay();

        $rows = DB::table('ai_usage_logs')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('
            DATE(created_at) as date,
            SUM(total_tokens) as tokens,
            COUNT(*) as requests
        ')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $tokens = [];
        $requests = [];

        $period = Carbon::parse($start)->daysUntil($end);

        foreach ($period as $date) {
            $key = $date->format('Y-m-d');

            $labels[] = $date->format('d M');
            $tokens[] = isset($rows[$key]) ? (int) $rows[$key]->tokens : 0;
            $requests[] = isset($rows[$key]) ? (int) $rows[$key]->requests : 0;
        }

        return response()->json([
            'labels' => $labels,
            'series' => [
                'tokens' => $tokens,
                'requests' => $requests,
            ],
        ]);
    }
}
