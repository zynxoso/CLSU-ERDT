<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RedirectDebugMiddleware
{
    /**
     * Handle an incoming request and log information to debug redirect loops.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Create log directory if it doesn't exist
        $logDir = storage_path('logs/redirects');
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Log path for this request
        $logPath = $logDir . '/redirect_' . date('Y-m-d') . '.log';

        // Prepare log message
        $logMessage = "==== Request at " . now()->format('Y-m-d H:i:s.u') . " ====\n";
        $logMessage .= "URL: " . $request->fullUrl() . "\n";
        $logMessage .= "Method: " . $request->method() . "\n";
        $logMessage .= "IP: " . $request->ip() . "\n";

        // Authentication status
        $logMessage .= "Auth Status: " . (Auth::check() ? 'Authenticated' : 'Not Authenticated') . "\n";
        if (Auth::check()) {
            $logMessage .= "User ID: " . Auth::id() . "\n";
            $logMessage .= "User Email: " . Auth::user()->email . "\n";
            $logMessage .= "User Role: " . Auth::user()->role . "\n";
        }

        // Session info
        $logMessage .= "Session ID: " . Session::getId() . "\n";
        $logMessage .= "Session Data: " . json_encode(Session::all()) . "\n";

        // Headers
        $logMessage .= "Request Headers:\n";
        foreach ($request->headers->all() as $key => $values) {
            $logMessage .= "  $key: " . implode(', ', $values) . "\n";
        }

        // Cookies
        $logMessage .= "Cookies:\n";
        foreach ($request->cookies->all() as $key => $value) {
            $logMessage .= "  $key: $value\n";
        }

        // Log referrer chain
        if (Session::has('referrer_chain')) {
            $referrerChain = Session::get('referrer_chain');
        } else {
            $referrerChain = [];
        }

        // Add current URL to chain
        $referrerChain[] = $request->fullUrl();

        // Keep only the last 10 URLs to avoid excessive size
        if (count($referrerChain) > 10) {
            $referrerChain = array_slice($referrerChain, -10);
        }

        // Save updated chain
        Session::put('referrer_chain', $referrerChain);

        // Detect potential redirect loop
        $urlCounts = array_count_values($referrerChain);
        $potentialLoop = false;
        $loopUrls = [];

        foreach ($urlCounts as $url => $count) {
            if ($count > 2) {
                $potentialLoop = true;
                $loopUrls[] = "$url (visited $count times)";
            }
        }

        // Log referrer chain
        $logMessage .= "Referrer Chain:\n";
        foreach ($referrerChain as $index => $url) {
            $logMessage .= "  " . ($index + 1) . ". $url\n";
        }

        // Log potential loop warning
        if ($potentialLoop) {
            $logMessage .= "!!! POTENTIAL REDIRECT LOOP DETECTED !!!\n";
            $logMessage .= "Loop URLs: " . implode(", ", $loopUrls) . "\n";
        }

        // Write log
        file_put_contents($logPath, $logMessage . "\n", FILE_APPEND);

        // Process the request
        return $next($request);
    }
}
