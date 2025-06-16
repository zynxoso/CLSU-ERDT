<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckPasswordExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // Skip password expiration check for certain routes
            $excludedRoutes = [
                'scholar.password.change',
                'scholar.password.update',
                'admin.password.change',
                'admin.password.update',
                'logout',
                'password.*'
            ];

            $currentRoute = $request->route()->getName();

            // Don't check password expiration on excluded routes
            if (in_array($currentRoute, $excludedRoutes) ||
                str_contains($currentRoute, 'password') ||
                str_contains($currentRoute, 'logout')) {
                return $next($request);
            }

            // Check for password expiration (90 days)
            if ($user->password_expires_at && Carbon::now()->greaterThan($user->password_expires_at)) {
                // Password has expired - force redirect to change password
                if ($user->role === 'scholar') {
                    return redirect()->route('scholar.password.change')
                        ->with('error', 'Your password has expired. Please change your password to continue.');
                } else {
                    return redirect()->route('admin.password.change')
                        ->with('error', 'Your password has expired. Please change your password to continue.');
                }
            }

            // Check for password expiring soon (within 7 days) and show warning
            if ($user->password_expires_at &&
                Carbon::now()->diffInDays($user->password_expires_at, false) <= 7 &&
                Carbon::now()->diffInDays($user->password_expires_at, false) >= 0) {

                $daysLeft = Carbon::now()->diffInDays($user->password_expires_at, false);
                if (!session('password_expiry_warning_shown')) {
                    session(['password_expiry_warning' => "Your password will expire in {$daysLeft} day(s). Please change it soon."]);
                    session(['password_expiry_warning_shown' => true]);
                }
            }

            // For default passwords or must_change_password, we now just let the notification banner handle it
            // No forced redirects - users can access the system but will see the notification
        }

        return $next($request);
    }
}
