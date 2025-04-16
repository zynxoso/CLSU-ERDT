<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectLoop
{
    /**
     * The maximum number of redirects allowed within a short time period
     * before it's considered a loop.
     */
    protected $maxRedirects = 5;

    /**
     * The time window in seconds to check for redirect loops.
     */
    protected $timeWindow = 5;

    /**
     * Handle an incoming request and prevent redirect loops.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current timestamp
        $now = time();

        // Get the redirects history from the session
        $redirectHistory = Session::get('redirect_history', []);

        // Clean up old redirects outside our time window
        $redirectHistory = array_filter($redirectHistory, function($timestamp) use ($now) {
            return ($now - $timestamp) <= $this->timeWindow;
        });

        // Count the redirects in our time window
        $redirectCount = count($redirectHistory);

        // Check if we're in a redirect loop
        if ($redirectCount >= $this->maxRedirects) {
            // Clear the session to break the loop
            Session::forget('redirect_history');

            // Log the loop detection
            $logMessage = "Redirect loop detected and blocked at: " . $request->fullUrl() . "\n";
            $logMessage .= "Redirects: " . $redirectCount . " in " . $this->timeWindow . " seconds\n";
            Log::error('Redirect Loop Detected: ' . $request->fullUrl());

            // Redirect to a safe page or show an error
            return response()->view('errors.redirect-loop', [
                'url' => $request->fullUrl(),
                'count' => $redirectCount,
                'timeWindow' => $this->timeWindow,
            ], 500);
        }

        // Get the response
        $response = $next($request);

        // If it's a redirect, record it
        if ($response->isRedirect()) {
            $redirectHistory[] = $now;
            Session::put('redirect_history', $redirectHistory);
        }

        return $response;
    }
}
