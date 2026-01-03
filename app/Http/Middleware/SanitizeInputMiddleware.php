<?php

namespace App\Http\Middleware;

use App\Helpers\SecurityHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        $except = ['content', 'left_content', 'right_content', 'description', 'manifesto'];

        array_walk_recursive($input, function (&$value, $key) use ($except) {
            if (is_string($value) && !in_array($key, $except)) {
                // If it looks like HTML (contains < >), sanitize but keep some tags
                if (preg_match('/<[^>]*>/', $value)) {
                    $value = SecurityHelper::sanitizeHtml($value);
                } else {
                    $value = SecurityHelper::cleanString($value);
                }
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
