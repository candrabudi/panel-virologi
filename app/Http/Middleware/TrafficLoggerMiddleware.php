<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemTrafficLog;
use Illuminate\Support\Facades\Auth;

class TrafficLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Calculate latency
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
        $latency = (microtime(true) - $startTime) * 1000;

        // Skip binary or ultra-large response logging if needed
        $payload = $request->except(['password', 'password_confirmation', 'api_token', '_token']);
        $queryParams = $request->query();
        
        // Capture important headers
        $headers = [
            'content-type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'referer' => $request->header('Referer'),
            'origin' => $request->header('Origin'),
            'connection' => $request->header('Connection'),
        ];

        try {
            SystemTrafficLog::create([
                'user_id' => Auth::id(),
                'method' => $request->method(),
                'path' => $request->path(),
                'query_params' => count($queryParams) > 0 ? $queryParams : null,
                'payload' => count($payload) > 0 ? $payload : null,
                'headers' => $headers,
                'response_status' => $response->getStatusCode(),
                'latency_ms' => $latency,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Log::error('TrafficLogger Error: ' . $e->getMessage());
        }
    }
}
