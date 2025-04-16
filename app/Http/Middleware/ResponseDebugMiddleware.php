<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResponseDebugMiddleware
{
    /**
     * Handle an incoming request and log response information to debug issues.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request
        $response = $next($request);

        // Create log directory if it doesn't exist
        $logDir = storage_path('logs/responses');
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Log path for this response
        $logPath = $logDir . '/response_' . date('Y-m-d') . '.log';

        // Prepare log message
        $logMessage = "==== Response at " . now()->format('Y-m-d H:i:s.u') . " ====\n";
        $logMessage .= "Request URL: " . $request->fullUrl() . "\n";
        $logMessage .= "Response Status: " . $response->getStatusCode() . "\n";

        // Log headers
        $logMessage .= "Response Headers:\n";
        foreach ($response->headers->all() as $key => $values) {
            $logMessage .= "  $key: " . implode(', ', $values) . "\n";
        }

        // Log auth status
        $logMessage .= "Auth Status: " . (Auth::check() ? 'Authenticated' : 'Not Authenticated') . "\n";
        if (Auth::check()) {
            $logMessage .= "User ID: " . Auth::id() . "\n";
            $logMessage .= "User Role: " . Auth::user()->role . "\n";
        }

        // Check for redirect
        if ($response->isRedirect()) {
            $logMessage .= "REDIRECT DETECTED!\n";
            $logMessage .= "Redirect Target: " . $response->headers->get('Location') . "\n";

            // If redirecting to the same URL, flag as potential loop
            if ($response->headers->get('Location') === $request->fullUrl()) {
                $logMessage .= "!!! WARNING: SELF-REDIRECT DETECTED !!!\n";
            }
        }

        // Write log
        file_put_contents($logPath, $logMessage . "\n", FILE_APPEND);

        return $response;
    }
}
