<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect based on guard and user role
                if ($guard === 'scholar') {
                    // Only redirect if user is actually a scholar
                    if ($user->role === 'scholar') {
                        return redirect()->route('scholar.dashboard');
                    } else {
                        // If not a scholar, logout from scholar guard
                        Auth::guard('scholar')->logout();
                        continue;
                    }
                } elseif ($guard === 'web' || $guard === null) {
                    // For web guard, check admin roles
                    if ($user->role === 'super_admin') {
                        return redirect()->route('super_admin.dashboard');
                    } elseif ($user->role === 'admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif ($user->role === 'scholar') {
                        // Scholar trying to access admin area, logout and redirect to scholar login
                        Auth::guard('web')->logout();
                        return redirect()->route('scholar.login');
                    }
                }
            }
        }

        return $next($request);
    }
}
