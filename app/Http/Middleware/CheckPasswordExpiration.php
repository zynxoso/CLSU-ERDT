<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Models\SiteSetting;
use App\Services\SystemSettingsService;

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
            // Get dynamic security settings
            $passwordExpiryDays = SystemSettingsService::getPasswordExpiryDays();
            $warningDays = 7; // Show warning 7 days before expiry

            // Skip password expiration check for certain routes
            $excludedRoutes = [
                'scholar.password.change',
                'scholar.password.update',
                'admin.password.change',
                'admin.password.update',
                'super_admin.password.change',
            'super_admin.password.update',
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

            // Check for password expiration using dynamic settings
            if ($user->password_expires_at && Carbon::now()->greaterThan($user->password_expires_at)) {
                // Password has expired - set notification
                session()->flash('warning', "Your password has expired. Please change your password soon. (Password expires every {$passwordExpiryDays} days)");
                session(['show_password_modal' => true]);
            }

            // For default passwords or must_change_password, we now just let the notification banner handle it
            // No forced redirects - users can access the system but will see the notification
            if ($user->must_change_password || $user->is_default_password) {
                session(['show_password_modal' => true]);
            }

            // Check for password expiring soon using dynamic warning period
            if ($user->password_expires_at &&
                Carbon::now()->diffInDays($user->password_expires_at, false) <= $warningDays &&
                Carbon::now()->diffInDays($user->password_expires_at, false) >= 0) {

                $daysLeft = Carbon::now()->diffInDays($user->password_expires_at, false);
                if (!session('password_expiry_warning_shown')) {
                    session(['password_expiry_warning' => "Your password will expire in {$daysLeft} day(s). Please change it soon. (Passwords expire every {$passwordExpiryDays} days)"]);
                    session(['password_expiry_warning_shown' => true]);
                }
            }
        }

        return $next($request);
    }
}
