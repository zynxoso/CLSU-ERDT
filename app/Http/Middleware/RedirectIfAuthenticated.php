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
                
                // Redirect based on user role and guard
                if ($guard === 'scholar' || $user->role === 'scholar') {
                    return redirect()->route('scholar.dashboard');
                } elseif ($guard === 'web' || in_array($user->role, ['admin', 'super_admin'])) {
                    return redirect()->route('admin.dashboard');
                }
                
                // Default fallback
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
