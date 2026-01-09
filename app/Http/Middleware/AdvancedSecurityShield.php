<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Advanced Security Shield (Mini-WAF)
 * Provides comprehensive protection against common web attacks:
 * 1. SQL Injection (SQLi) Detection
 * 2. Cross-Site Scripting (XSS) Prevention
 * 3. Local/Remote File Inclusion (LFI/RFI) Blocking
 * 4. Directory Traversal Defense
 * 5. Session Fingerprinting
 * 6. Automated IP Shunning for Suspicious Activity
 */
class AdvancedSecurityShield
{
    // Attack Patterns (Regex)
    protected $patterns = [
        'sqli' => '/(union\s+select|insert\s+into|update\s+.*\s+set|delete\s+from|drop\s+table|--|#|\' OR \'1\'=\'1\'|\' OR 1=1|%27%20OR%20%271%27%3D%271%27)/i',
        'xss'  => '/(<script|javascript:|on\w+\s*=|alert\(|base64|document\.cookie|eval\(|window\.location)/i',
        'lfi_traversal' => '/(\.\.\/|\.\.\\\\|etc\/passwd|proc\/self|php:\/\/filter|php:\/\/input)/i',
        'rfi' => '/(https?|ftp|php|data):(\/\/|\\\\)/i',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // 1. Check if IP is currently shunned (blocked)
        if (Cache::has("shunned_ip:$ip")) {
            Log::alert("Blocked access from shunned IP: $ip attempt on " . $request->fullUrl());
            return response()->json([
                'status' => 'error',
                'message' => 'Akses diblokir karena aktivitas mencurigakan. Silakan hubungi administrator.',
                'incident_id' => Cache::get("shunned_ip:$ip")
            ], 403);
        }

        // 2. Validate Session Fingerprint (For Logged in Users)
        if (auth()->check()) {
            $this->validateSessionFingerprint($request);
        }

        // 3. Scan all inputs for attack patterns
        $this->scanInputs($request);

        // 4. Continue to Next Middleware
        $response = $next($request);

        // 5. Apply Secure Headers
        return $this->applySecureHeaders($response);
    }

    /**
     * Scan inputs, headers, and URL for malicious patterns.
     */
    protected function scanInputs(Request $request)
    {
        $inputs = $request->all();
        $source = [
            'input' => $inputs,
            'url' => $request->getRequestUri(),
            'user_agent' => $request->userAgent()
        ];

        foreach ($source as $type => $data) {
            $stringToScan = is_array($data) ? json_encode($data) : $data;

            foreach ($this->patterns as $attackType => $regex) {
                // EXCEPTION: Allow Rich Text and URLs for AI Knowledge Base and Articles
                if (($request->is('ai/knowledge*') || $request->is('articles*')) && in_array($attackType, ['rfi', 'sqli', 'xss'])) {
                    continue;
                }

                if (preg_match($regex, $stringToScan)) {
                    $this->handleIntrusion($request, $attackType);
                }
            }
        }
    }

    /**
     * Logic for handling detected intrusions.
     */
    protected function handleIntrusion(Request $request, string $type)
    {
        $ip = $request->ip();
        $incidentId = 'INC-' . strtoupper(uniqid());
        $counterKey = "incident_count:$ip";
        
        $count = Cache::increment($counterKey);
        if ($count === 1) Cache::put($counterKey, 1, now()->addHour());

        Log::warning("[ADVANCED SECURITY] $type attack detected!", [
            'incident_id' => $incidentId,
            'ip' => $ip,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'payload' => $request->except(['password', 'api_token']),
            'user_id' => auth()->id() ?? 'Guest'
        ]);

        // Auto-shun if 5+ attempts in an hour
        if ($count >= 5) {
            Cache::put("shunned_ip:$ip", $incidentId, now()->addDays(1));
            Log::emergency("IP $ip HAS BEEN SHUNNED for repeated attacks. Final Incident: $incidentId");
            
            abort(403, "Sistem keamanan mendeteksi serangan. IP Anda diblokir sementara. Incident ID: $incidentId");
        }

        abort(403, "Permintaan ditolak oleh sistem keamanan (WAF). Kode: $incidentId");
    }

    /**
     * Session Binding to IP and User Agent to prevent Session Hijacking.
     */
    protected function validateSessionFingerprint(Request $request)
    {
        $session = $request->session();
        $fingerprint = sha1($request->ip() . $request->userAgent());

        if (!$session->has('security_fingerprint')) {
            $session->put('security_fingerprint', $fingerprint);
            return;
        }

        if ($session->get('security_fingerprint') !== $fingerprint) {
            Log::alert("Session hijacking attempt or IP change detected for user " . auth()->id(), [
                'original_fingerprint' => $session->get('security_fingerprint'),
                'new_fingerprint' => $fingerprint,
                'ip' => $request->ip()
            ]);

            auth()->logout();
            $session->invalidate();
            
            abort(403, 'Sesi tidak valid. Aktivitas mencurigakan terdeteksi, silakan login kembali.');
        }
    }

    /**
     * Hardened Security Headers.
     */
    protected function applySecureHeaders(Response $response): Response
    {
        $headers = [
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'no-referrer-when-downgrade',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https:;",
        ];

        foreach ($headers as $key => $value) {
            // Only set if not already present or to overwrite basic ones
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
