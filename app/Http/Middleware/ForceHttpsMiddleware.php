<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SystemSettingsService;

class ForceHttpsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if HTTPS should be forced from system settings
        if (SystemSettingsService::isHttpsForced() && !$request->secure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
