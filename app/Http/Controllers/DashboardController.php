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
     * Main Analytics Summary.
     */
    public function aiAnalyticsSummary(): JsonResponse
    {
        $this->authorizeView();

        $now = Carbon::now();
        $startThisPeriod = $now->copy()->subDays(30);
        $startPrevPeriod = $now->copy()->subDays(60);

        // AI Usage Stats
        $aiUsage = DB::table('ai_usage_logs')
            ->where('created_at', '>=', $startThisPeriod)
            ->selectRaw('
                SUM(total_tokens) as total_units,
                COUNT(*) as total_requests,
                COUNT(DISTINCT ip_address) as active_ips,
                SUM(CASE WHEN is_blocked = 1 THEN 1 ELSE 0 END) as blocked
            ')
            ->first();

        $previousAi = DB::table('ai_usage_logs')
            ->whereBetween('created_at', [$startPrevPeriod, $startThisPeriod])
            ->selectRaw('SUM(total_tokens) as total_units')
            ->first();

        $aiGrowth = 0;
        if ($previousAi && $previousAi->total_units > 0) {
            $aiGrowth = round(
                (($aiUsage->total_units - $previousAi->total_units) / $previousAi->total_units) * 100,
                1
            );
        }

        // Leak Check Stats
        $leakStats = DB::table('leak_check_logs')
            ->selectRaw('
                COUNT(*) as total_checks,
                SUM(leak_count) as total_leaks_found,
                COUNT(DISTINCT user_id) as active_users
            ')
            ->first();

        // Cyber Attack Stats
        $attackStats = DB::table('cyber_attacks')
            ->selectRaw('
                COUNT(*) as total_attacks,
                COUNT(DISTINCT source_ip) as unique_attackers,
                AVG(confidence_score) as avg_confidence
            ')
            ->first();

        // General Site Stats & Role Breakdown
        $roleBreakdown = DB::table('users')
            ->selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        $generalStats = [
            'total_articles' => DB::table('articles')->count(),
            'total_ebooks'   => DB::table('ebooks')->count(),
            'total_products' => DB::table('products')->count(),
            'total_users'    => [
                'total' => DB::table('users')->count(),
                'admin' => $roleBreakdown['admin'] ?? 0,
                'editor' => $roleBreakdown['editor'] ?? 0,
                'user' => $roleBreakdown['user'] ?? 0,
            ],
            'total_services' => DB::table('cyber_security_services')->count(),
            'total_chat_sessions' => DB::table('ai_chat_sessions')->count(),
            'cyber_attacks' => [
                'total' => (int) $attackStats->total_attacks,
                'unique_attackers' => (int) $attackStats->unique_attackers,
                'high_priority' => DB::table('cyber_attacks')->where('confidence_score', '>', 0.8)->count(),
            ],
            'leak_checks' => [
                'total' => (int) $leakStats->total_checks,
                'found' => (int) $leakStats->total_leaks_found,
            ]
        ];

        return ResponseHelper::ok([
            'ai' => [
                'total_units'    => (int) ($aiUsage->total_units ?? 0),
                'total_requests' => (int) ($aiUsage->total_requests ?? 0),
                'active_ips'     => (int) ($aiUsage->active_ips ?? 0),
                'success_rate'   => $aiUsage->total_requests > 0
                    ? round((($aiUsage->total_requests - $aiUsage->blocked) / $aiUsage->total_requests) * 100, 2)
                    : 100,
                'growth'         => $aiGrowth,
            ],
            'site' => $generalStats,
            'security' => $this->getSecuritySummaryData()
        ]);
    }

    /**
     * Internal: Security breakdown.
     */
    private function getSecuritySummaryData(): array
    {
        $topAttackers = DB::table('cyber_attacks')
            ->selectRaw('source_country as country, COUNT(*) as count')
            ->groupBy('source_country')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $attackTypes = DB::table('cyber_attacks')
            ->selectRaw('attack_type, COUNT(*) as count')
            ->groupBy('attack_type')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return [
            'top_countries' => $topAttackers,
            'attack_types' => $attackTypes,
        ];
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
                COUNT(*) as requests,
                SUM(CASE WHEN is_blocked = 1 THEN 1 ELSE 0 END) as blocked,
                COUNT(DISTINCT ip_address) as unique_ips
            ')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $tokens = [];
        $requests = [];
        $blocked = [];
        $unique_ips = [];

        $period = Carbon::parse($start)->daysUntil($end);

        foreach ($period as $date) {
            $key = $date->format('Y-m-d');

            $labels[] = $date->format('d M');
            $tokens[] = isset($rows[$key]) ? (int) $rows[$key]->tokens : 0;
            $requests[] = isset($rows[$key]) ? (int) $rows[$key]->requests : 0;
            $blocked[] = isset($rows[$key]) ? (int) $rows[$key]->blocked : 0;
            $unique_ips[] = isset($rows[$key]) ? (int) $rows[$key]->unique_ips : 0;
        }

        return ResponseHelper::ok([
            'labels' => $labels,
            'series' => [
                'tokens'   => $tokens,
                'requests' => $requests,
                'blocked'  => $blocked,
                'unique_ips' => $unique_ips
            ],
        ]);
    }
}
