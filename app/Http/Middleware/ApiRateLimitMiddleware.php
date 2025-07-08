<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $limitType
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $limitType = 'default'): Response
    {
        $limits = $this->getRateLimits();
        $config = $limits[$limitType] ?? $limits['default'];

        // Check for dynamic rate limiting
        $dynamicLimit = $this->getDynamicRateLimit($request);
        if ($dynamicLimit) {
            $config = $dynamicLimit;
        }

        // Check if IP is temporarily blocked
        if ($this->isIpBlocked($request->ip())) {
            Log::critical('Blocked IP attempted access', [
                'ip' => $request->ip(),
                'endpoint' => $request->path(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'message' => 'Access temporarily restricted due to suspicious activity',
                'contact' => 'Please contact support if you believe this is an error'
            ], 429, [
                'Retry-After' => 3600, // 1 hour
                'X-Block-Reason' => 'Suspicious activity detected',
            ]);
        }

        $key = $this->resolveRequestSignature($request, $limitType);

        $executed = RateLimiter::attempt(
            $key,
            $config['maxAttempts'],
            function () use ($next, $request) {
                return $next($request);
            },
            $config['decayMinutes'] * 60
        );

        if (!$executed) {
            $retryAfter = RateLimiter::availableIn($key);

            // Log rate limit exceeded attempts for security monitoring
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'user_id' => Auth::id(),
                'endpoint' => $request->path(),
                'limit_type' => $limitType,
                'user_agent' => $request->userAgent(),
            ]);

            // Check for repeat offenders and apply temporary IP blocking
            $this->checkForRepeatOffender($request->ip());

            return response()->json([
                'message' => 'Too many requests',
                'retry_after' => $retryAfter,
                'limit_type' => $limitType
            ], 429, [
                'Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => $config['maxAttempts'],
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
            ]);
        }

        // Add rate limit headers to successful responses
        $response = $executed;
        $remaining = RateLimiter::remaining($key, $config['maxAttempts']);

        $response->headers->add([
            'X-RateLimit-Limit' => $config['maxAttempts'],
            'X-RateLimit-Remaining' => $remaining,
            'X-RateLimit-Reset' => RateLimiter::availableIn($key) + time(),
        ]);

        return $response;
    }

    /**
     * Resolve the rate limiting key for the request.
     */
    protected function resolveRequestSignature(Request $request, string $limitType): string
    {
        $userId = Auth::id();
        $ip = $request->ip();

        // Use user ID if authenticated, otherwise use IP
        $identifier = $userId ? "user:{$userId}" : "ip:{$ip}";

        return "rate_limit:{$limitType}:{$identifier}";
    }

    /**
     * Get rate limiting configuration.
     */
    protected function getRateLimits(): array
    {
        return [
            // Default rate limit for general API endpoints
            'default' => [
                'maxAttempts' => 60,  // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Strict limits for authentication endpoints
            'auth' => [
                'maxAttempts' => 5,   // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Moderate limits for AJAX endpoints
            'ajax' => [
                'maxAttempts' => 100, // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Lenient limits for public endpoints
            'public' => [
                'maxAttempts' => 200, // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Strict limits for file upload endpoints
            'upload' => [
                'maxAttempts' => 10,  // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Moderate limits for data export endpoints
            'export' => [
                'maxAttempts' => 5,   // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Very strict limits for admin actions
            'admin' => [
                'maxAttempts' => 30,  // requests
                'decayMinutes' => 1,  // per minute
            ],

            // Extra strict for sensitive operations
            'sensitive' => [
                'maxAttempts' => 3,   // requests
                'decayMinutes' => 1,  // per minute
            ],
        ];
    }

    /**
     * Get dynamic rate limiting configuration if applied
     */
    protected function getDynamicRateLimit(Request $request): ?array
    {
        $dynamicLimitKey = "dynamic_rate_limit:{$request->ip()}";
        return Cache::get($dynamicLimitKey);
    }

    /**
     * Check if IP is temporarily blocked
     */
    protected function isIpBlocked(string $ip): bool
    {
        $blockKey = "blocked_ip:{$ip}";
        return Cache::has($blockKey);
    }

    /**
     * Check for repeat offenders and apply temporary IP blocking
     */
    protected function checkForRepeatOffender(string $ip): void
    {
        $violationKey = "rate_violations:{$ip}";
        $violations = Cache::get($violationKey, 0);
        $violations++;

        Cache::put($violationKey, $violations, 3600); // Track for 1 hour

        // If more than 10 violations in an hour, temporarily block the IP
        if ($violations >= 10) {
            $blockKey = "blocked_ip:{$ip}";
            Cache::put($blockKey, [
                'blocked_at' => now()->toDateTimeString(),
                'violations' => $violations,
                'reason' => 'Excessive rate limit violations'
            ], 3600); // Block for 1 hour

            Log::critical('IP temporarily blocked due to excessive rate limit violations', [
                'ip' => $ip,
                'violations' => $violations,
                'block_duration' => '1 hour'
            ]);
        }
    }
}
