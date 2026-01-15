<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogSuspiciousRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // --- BOTSHIELD PROTECTION ---
        // Fetch settings from DB (In production, use Cache::remember)
        $settings = \Illuminate\Support\Facades\DB::table('security_settings')->pluck('value', 'key');
        
        // 1. Rate Limiting (Strict)
        if (($settings['rate_limiting'] ?? 'false') === 'true') {
            $key = 'botshield_rate:' . $request->ip();
            // Limit: 60 requests per minute per IP
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 60)) {
                 $loc = $this->getMockLocation($request->ip());
                 \Illuminate\Support\Facades\DB::table('server_anomalies')->insert([
                    'event_type' => 'rate_limit_exceeded',
                    'ip_address' => $request->ip(),
                    'country_code' => $loc['code'],
                    'country_name' => $loc['name'],
                    'latitude' => $loc['lat'],
                    'longitude' => $loc['lon'],
                    'details' => 'Blocked by BotShield Rate Limiting',
                    'severity' => 'medium',
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
                return response('BotShield: Rate limit exceeded. Please slow down.', 429);
            }
            \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
        }

        // 2. Challenge Mode (Bot Filtering)
        if (($settings['challenge_mode'] ?? 'false') === 'true') {
             $ua = strtolower($request->userAgent() ?? '');
             $suspiciousBots = ['curl', 'wget', 'python', 'java', 'libwww', 'scrapy', 'httpclient'];
             foreach($suspiciousBots as $bot) {
                 if (str_contains($ua, $bot)) {
                     $loc = $this->getMockLocation($request->ip());
                     \Illuminate\Support\Facades\DB::table('server_anomalies')->insert([
                        'event_type' => 'bot_blocked',
                        'ip_address' => $request->ip(),
                        'country_code' => $loc['code'],
                        'country_name' => $loc['name'],
                        'latitude' => $loc['lat'],
                        'longitude' => $loc['lon'],
                        'details' => "Blocked automated client: $bot",
                        'severity' => 'low',
                        'created_at' => now(), 
                        'updated_at' => now()
                    ]);
                    return response('BotShield: Automated access denied.', 403);
                 }
             }
        }
        
        // 3. Geo-Fencing (Simulation using simulated high-risk IP range)
        if (($settings['geo_fencing'] ?? 'false') === 'true') {
             // Mock: Block IPs starting with specific range for demo
             if (str_starts_with($request->ip(), '100.200')) {
                  return response('BotShield: Access from this region is blocked.', 403);
             }
        }

        // --- ANOMALY DETECTION (SQLi, XSS) ---
        $patterns = [
            'sqli_attempt' => '/(union\s+select|information_schema|drop\s+table|or\s+1=1|--)/i',
            'xss_attempt' => '/(<script>|javascript:|onerror=|onload=|alert\()/i',
            'path_traversal' => '/(\.\.\/|\.\.\\\\)/',
        ];

        // Check Input
        foreach ($request->input() as $key => $value) {
            if (is_string($value)) {
                foreach ($patterns as $type => $pattern) {
                    if (preg_match($pattern, $value)) {
                        $loc = $this->getMockLocation($request->ip());
                        \Illuminate\Support\Facades\DB::table('server_anomalies')->insert([
                            'event_type' => $type,
                            'ip_address' => $request->ip(),
                            'country_code' => $loc['code'],
                            'country_name' => $loc['name'],
                            'latitude' => $loc['lat'],
                            'longitude' => $loc['lon'],
                            'details' => "Pattern detected in input '{$key}': " . \Illuminate\Support\Str::limit($value, 50),
                            'severity' => 'high',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        break 2;
                }
            }
        }
    }

        // Check URI for suspicious path scans
        $suspiciousPaths = ['/wp-admin', '/phpmyadmin', '/.env', '/config.json'];
        foreach($suspiciousPaths as $path) {
            if(str_contains($request->getRequestUri(), $path)) {
                 $loc = $this->getMockLocation($request->ip());
                 \Illuminate\Support\Facades\DB::table('server_anomalies')->insert([
                    'event_type' => 'probe_scan',
                    'ip_address' => $request->ip(),
                    'country_code' => $loc['code'],
                    'country_name' => $loc['name'],
                    'latitude' => $loc['lat'],
                    'longitude' => $loc['lon'],
                    'details' => "Suspicious path scan: " . $request->getRequestUri(),
                    'severity' => 'medium',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                break;
            }
        }

        return $next($request);
    }
    private function getMockLocation($ip) {
        // Simple mock based on IP prefix or random
        $locations = [
            ['code' => 'ID', 'name' => 'Indonesia', 'lat' => -0.7893, 'lon' => 113.9213],
            ['code' => 'US', 'name' => 'United States', 'lat' => 37.0902, 'lon' => -95.7129],
            ['code' => 'CN', 'name' => 'China', 'lat' => 35.8617, 'lon' => 104.1954],
            ['code' => 'RU', 'name' => 'Russia', 'lat' => 61.5240, 'lon' => 105.3188],
            ['code' => 'BR', 'name' => 'Brazil', 'lat' => -14.2350, 'lon' => -51.9253],
            ['code' => 'DE', 'name' => 'Germany', 'lat' => 51.1657, 'lon' => 10.4515],
             ['code' => 'IN', 'name' => 'India', 'lat' => 20.5937, 'lon' => 78.9629],
        ];
        
        // Deterministic 'random' based on IP length to be stable for same IP
        $index = strlen($ip) % count($locations);
        return $locations[$index];
    }
}

