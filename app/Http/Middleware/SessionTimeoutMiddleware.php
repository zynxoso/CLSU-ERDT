<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isWebAuthenticated = Auth::guard('web')->check();
        $isScholarAuthenticated = Auth::guard('scholar')->check();
        
        // Check if any user is authenticated
        if ($isWebAuthenticated || $isScholarAuthenticated) {
            $lastActivity = Session::get('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // Convert minutes to seconds
            
            // If last activity exists and session has expired
            if ($lastActivity && (time() - $lastActivity) > $sessionLifetime) {
                // Determine user type before logout
                $isScholar = false;
                $isAdmin = false;
                
                if ($isWebAuthenticated) {
                    $user = Auth::guard('web')->user();
                    $isAdmin = $user && in_array($user->role, ['admin', 'super_admin']);
                } elseif ($isScholarAuthenticated) {
                    $user = Auth::guard('scholar')->user();
                    $isScholar = true; // User was authenticated via scholar guard
                }
                
                // Clear all authentication sessions and session data
                Auth::guard('web')->logout();
                Auth::guard('scholar')->logout();
                Session::invalidate();
                Session::regenerateToken();
                Session::flush();
                
                // Redirect to login with timeout message
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired'], 401);
                }
                
                // Redirect to appropriate login page based on user type
                $loginRoute = $isScholar ? 'scholar-login' : 'login';
                return redirect()->route($loginRoute)->with('error', 'Your session has expired. Please login again.');
            }
            
            // Update last activity timestamp
            Session::put('last_activity', time());
        }
        
        return $next($request);
    }
}
