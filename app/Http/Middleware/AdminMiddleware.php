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
        // Check authentication using web guard specifically
        if (!Auth::guard('web')->check()) {
            // Clear any scholar authentication that might exist
            Auth::guard('scholar')->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        $user = Auth::guard('web')->user();
        
        // Check admin or super_admin role
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            
            // Logout from web guard and redirect to scholar login if they're a scholar
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            $redirectRoute = $user->role === 'scholar' ? 'scholar.login' : 'login';
            return redirect()->route($redirectRoute)
                ->with('error', 'You do not have permission to access the admin area.');
        }

        return $next($request);
    }
}
