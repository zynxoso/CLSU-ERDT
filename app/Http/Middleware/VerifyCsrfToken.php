<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // API endpoints that need CSRF exemption
        'api/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check for session mismatch issues
        if ($request->isMethod('post') && !$request->expectsJson()) {
            // Ensure session is started
            if (!$request->hasSession()) {
                Log::warning('CSRF: No session found for POST request', [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip()
                ]);
            }
            
            // Log CSRF token mismatches for debugging
            if (!$this->tokensMatch($request)) {
                Log::warning('CSRF token mismatch', [
                    'url' => $request->fullUrl(),
                    'session_token' => $request->session()->token(),
                    'request_token' => $request->input('_token') ?: $request->header('X-CSRF-TOKEN'),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ]);
            }
        }

        return parent::handle($request, $next);
    }
}
