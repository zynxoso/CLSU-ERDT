<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoringMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // DDoS Detection - Check for suspicious patterns
        $this->detectSuspiciousActivity($request);

        // Monitor request frequency per IP
        $this->monitorRequestFrequency($request);

        $response = $next($request);

        // Calculate performance metrics
        $executionTime = microtime(true) - $startTime;
        $memoryUsage = memory_get_peak_usage(true) - $startMemory;

        // Log performance metrics for monitoring
        $this->logPerformanceMetrics($request, $executionTime, $memoryUsage);

        // Add performance headers for monitoring
        $response->headers->set('X-Response-Time', round($executionTime * 1000, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));

        return $response;
    }

    /**
     * Detect suspicious activity patterns that might indicate DDoS
     */
    private function detectSuspiciousActivity(Request $request): void
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $path = $request->path();

        // Check for bot-like behavior
        $botPatterns = [
            '/bot/i', '/crawler/i', '/spider/i', '/scraper/i',
            '/curl/i', '/wget/i', '/python/i', '/java/i'
        ];

        $isSuspiciousUserAgent = false;
        foreach ($botPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $isSuspiciousUserAgent = true;
                break;
            }
        }

        // Check for rapid successive requests
        $requestKey = "request_tracking:{$ip}";
        $requests = Cache::get($requestKey, []);
        $currentTime = now()->timestamp;

        // Clean old requests (older than 1 minute)
        $requests = array_filter($requests, function($timestamp) use($currentTime) {
            return ($currentTime - $timestamp) < 60;
        });

        $requests[] = $currentTime;
        Cache::put($requestKey, $requests, 300); // Store for 5 minutes

        // If more than 50 requests in 60 seconds, flag as suspicious
        if (count($requests) > 50) {
            Log::warning('Potential DDoS detected - High request frequency', [
                'ip' => $ip,
                'requests_per_minute' => count($requests),
                'user_agent' => $userAgent,
                'path' => $path,
                'timestamp' => $currentTime
            ]);

            // Increase rate limiting for this IP
            $this->applyDynamicRateLimit($ip);
        }

        // Log suspicious user agents
        if ($isSuspiciousUserAgent) {
            Log::info('Suspicious user agent detected', [
                'ip' => $ip,
                'user_agent' => $userAgent,
                'path' => $path
            ]);
        }
    }

    /**
     * Monitor request frequency per IP address
     */
    private function monitorRequestFrequency(Request $request): void
    {
        $ip = $request->ip();
        $endpoint = $request->path();

        // Track requests per endpoint per IP
        $endpointKey = "endpoint_requests:{$ip}:{$endpoint}";
        $endpointRequests = Cache::get($endpointKey, 0);

        Cache::put($endpointKey, $endpointRequests + 1, 300);

        // Alert if same endpoint is hit too frequently
        if ($endpointRequests > 20) { // More than 20 requests to same endpoint in 5 minutes
            Log::warning('Potential endpoint flooding detected', [
                'ip' => $ip,
                'endpoint' => $endpoint,
                'request_count' => $endpointRequests + 1
            ]);
        }
    }

    /**
     * Apply dynamic rate limiting for suspicious IPs
     */
    private function applyDynamicRateLimit(string $ip): void
    {
        $dynamicLimitKey = "dynamic_rate_limit:{$ip}";

        // Apply stricter rate limiting for 1 hour
        Cache::put($dynamicLimitKey, [
            'maxAttempts' => 10, // Reduced from normal limits
            'decayMinutes' => 1,
            'applied_at' => now()->toDateTimeString()
        ], 3600); // 1 hour

        Log::info('Dynamic rate limiting applied', [
            'ip' => $ip,
            'duration' => '1 hour',
            'new_limit' => '10 requests per minute'
        ]);
    }

    /**
     * Log performance metrics
     */
    private function logPerformanceMetrics(Request $request, float $executionTime, int $memoryUsage): void
    {
        // Only log slow requests or high memory usage
        if ($executionTime > 2.0 || $memoryUsage > 50 * 1024 * 1024) { // 2 seconds or 50MB
            Log::info('Performance metrics', [
                'ip' => $request->ip(),
                'method' => $request->method(),
                'path' => $request->path(),
                'execution_time' => round($executionTime, 4) . 's',
                'memory_usage' => $this->formatBytes($memoryUsage),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }
}
