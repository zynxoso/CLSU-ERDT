<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log middleware execution
        $logPath = storage_path('logs/super_admin_middleware.log');
        $logMessage = "SuperAdminMiddleware executed at " . now() . "\n";
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

        // Check super admin role
        if (Auth::user()->role !== 'super_admin') {
            $logMessage .= "UNAUTHORIZED: User is not a super admin\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            // Redirect to appropriate dashboard instead of abort
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access the super admin area.');
        }

        $logMessage .= "AUTHORIZED: User is super admin\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        return $next($request);
    }
}
