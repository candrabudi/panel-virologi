<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Consistent authorization check for dashboard/analytical data.
     * Restricted to admin or editor.
     */
    private function authorizeView(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || $user->role === 'editor')) {
            return;
        }

        Log::warning("Unauthorized attempt to access Dashboard analytics by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to dashboard data');
    }

    /**
     * Legacy summary method (if still used by frontend).
     */
    public function summary(): JsonResponse
    {
        $this->authorizeView();

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

        return ResponseHelper::ok([
            'total_units'      => (int) $totalTokens,
            'total_requests'   => (int) $totalRequests,
            'active_ips'       => (int) $activeCustomers,
            'success_rate'     => $conversionRate,
            'total_orders'     => $totalRequests, // Legacy support
        ]);
    }

    /**
     * Main AI Analytics Summary.
     */
    public function aiAnalyticsSummary(): JsonResponse
    {
        $this->authorizeView();

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
        if ($previous && $previous->total_units > 0) {
            $growth = round(
                (($current->total_units - $previous->total_units) / $previous->total_units) * 100,
                1
            );
        }

        return ResponseHelper::ok([
            'total_units'    => (int) ($current->total_units ?? 0),
            'total_requests' => (int) ($current->total_requests ?? 0),
            'active_ips'     => (int) ($current->active_ips ?? 0),
            'success_rate'   => $current->total_requests > 0
                ? round((($current->total_requests - $current->blocked) / $current->total_requests) * 100, 2)
                : 100,
            'growth'         => $growth,
        ]);
    }

    /**
     * Daily AI Traffic chart data.
     */
    public function aiTrafficDaily(): JsonResponse
    {
        $this->authorizeView();

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

        return ResponseHelper::ok([
            'labels' => $labels,
            'series' => [
                'tokens'   => $tokens,
                'requests' => $requests,
            ],
        ]);
    }
}
