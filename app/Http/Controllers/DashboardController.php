<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function aiTrafficDaily(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $rows = DB::table('ai_usage_logs')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_requests,
                COUNT(DISTINCT ip_address) as active_ips,
                SUM(total_tokens) as total_tokens,
                SUM(CASE WHEN is_blocked = 1 THEN 1 ELSE 0 END) as blocked_requests
            ')
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $labels = [];
        $requests = [];
        $activeIps = [];
        $successRate = [];
        $tokens = [];

        foreach ($rows as $row) {
            $labels[] = Carbon::parse($row->date)->format('d M');
            $requests[] = (int) $row->total_requests;
            $activeIps[] = (int) $row->active_ips;
            $tokens[] = (int) $row->total_tokens;

            $successRate[] = $row->total_requests > 0
                ? round((($row->total_requests - $row->blocked_requests) / $row->total_requests) * 100, 2)
                : 0;
        }

        return response()->json([
            'labels' => $labels,
            'series' => [
                'requests' => $requests,
                'active_ips' => $activeIps,
                'success_rate' => $successRate,
                'tokens' => $tokens,
            ],
        ]);
    }
}
