<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        // Check authentication using web guard specifically
        if (!Auth::guard('web')->check()) {
            $logMessage .= "UNAUTHORIZED: User not authenticated on web guard\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            
            // Clear any scholar authentication that might exist
            Auth::guard('scholar')->logout();
            session()->invalidate();
                session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        $user = Auth::guard('web')->user();
        
        // Log user info
        $logMessage .= "User ID: " . $user->id . "\n";
        $logMessage .= "User Email: " . $user->email . "\n";
        $logMessage .= "User Role: " . $user->role . "\n";

        // Check admin or super_admin role
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            $logMessage .= "UNAUTHORIZED: User is not an admin or super_admin\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            
            // Logout from web guard and redirect to scholar login if they're a scholar
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            $redirectRoute = $user->role === 'scholar' ? 'scholar-login' : 'login';
            return redirect()->route($redirectRoute)
                ->with('error', 'You do not have permission to access the admin area.');
        }

        $logMessage .= "AUTHORIZED: User is admin or super_admin\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        return $next($request);
    }
}
