<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        return view('security.index');
    }

    public function getStats()
    {
        // 1. Real Data from Cyber Attacks
        $totalAttacks = \DB::table('cyber_attacks')->count();
        $recentAttacks = \DB::table('cyber_attacks')->latest()->take(5)->get();
        
        // 2. Real Data from AI Usage (Anomalies)
        $aiBlocked = \DB::table('ai_usage_logs')->where('is_blocked', true)->count();
        $recentAnomalies = \DB::table('ai_usage_logs')
            ->where('is_blocked', true)
            ->latest()
            ->take(5)
            ->select('ip_address', 'block_reason', 'created_at')
            ->get();

        // 3. Data from Security Metrics (Real Table)
        $latestMetric = \DB::table('security_metrics')->orderBy('metric_date', 'desc')->first();
        
        $botnetStats = [
            'active_botnets' => $latestMetric ? $latestMetric->active_botnets : 0,
            'c2_nodes_blocked' => $latestMetric ? $latestMetric->c2_nodes_blocked : 0,
            'traffic_scrubbed' => $latestMetric ? $latestMetric->traffic_scrubbed : '0 GB',
        ];
        $threatLevel = $latestMetric ? $latestMetric->threat_level : 'LOW';

        // 4. Targeted Attacks (Specific to this site)
        $targetedAttacks = \DB::table('targeted_attacks')->latest()->take(5)->get();

        // 5. Server Health & Infrastructure
        $serverHealth = \DB::table('server_health_metrics')->latest()->first();
        $serverTraffic = \DB::table('server_health_metrics')->latest()->take(30)->get()->reverse()->values();

        // 6. Security Events (Logs & Anomalies)
        $accessLogs = \DB::table('access_logs')->latest()->take(5)->get();
        $serverAnomalies = \DB::table('server_anomalies')->latest()->take(5)->get();

        // 7. Security Settings
        $settings = \DB::table('security_settings')->pluck('value', 'key');

        return response()->json([
            'status' => true,
            'data' => [
                'attacks' => [
                    'total' => $totalAttacks,
                    'recent' => $recentAttacks
                ],
                'anomalies' => [
                    'total' => $aiBlocked,
                    'recent' => $recentAnomalies
                ],
                'targeted_attacks' => $targetedAttacks,
                'botshield' => $botnetStats,
                'threat_level' => $threatLevel, 
                'server_health' => [
                    'current' => $serverHealth,
                    'history' => $serverTraffic
                ],
                'security_events' => [
                    'access_logs' => $accessLogs,
                    'anomalies' => $serverAnomalies
                ],
                'settings' => $settings
            ]
        ]);
    }

    public function updateSettings(Request $request) {
        $key = $request->input('key');
        $value = $request->input('value');
        
        \DB::table('security_settings')->where('key', $key)->update(['value' => $value, 'updated_at' => now()]);
        
        return response()->json(['status' => true, 'message' => 'Setting updated']);
    }
}
