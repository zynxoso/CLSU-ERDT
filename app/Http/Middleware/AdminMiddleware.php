<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log middleware execution
        $logPath = storage_path('logs/admin_middleware.log');
        $logMessage = "AdminMiddleware executed at " . now() . "\n";
        $logMessage .= "Request URL: " . $request->fullUrl() . "\n";

        // Ensure log directory exists
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        // Check authentication
        if (!Auth::check()) {
            $logMessage .= "UNAUTHORIZED: User not authenticated\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            // Redirect to login instead of abort which may cause redirect loops
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        // Log user info
        $logMessage .= "User ID: " . Auth::id() . "\n";
        $logMessage .= "User Email: " . Auth::user()->email . "\n";
        $logMessage .= "User Role: " . Auth::user()->role . "\n";

        // Check admin role
        if (Auth::user()->role !== 'admin') {
            $logMessage .= "UNAUTHORIZED: User is not an admin\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            // Redirect to appropriate dashboard instead of abort
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access the admin area.');
        }

        $logMessage .= "AUTHORIZED: User is admin\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        return $next($request);
    }
}
