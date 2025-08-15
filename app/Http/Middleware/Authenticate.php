<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Check if this is a scholar-related route or request
            $isScholarContext = $this->isScholarContext($request);
            
            return $isScholarContext ? route('scholar.login') : route('login');
        }

        return null;
    }

    /**
     * Determine if the request is in a scholar context.
     */
    private function isScholarContext(Request $request): bool
    {
        // Check URL patterns that indicate scholar context
        if ($request->is('scholar/*') || 
            $request->routeIs('scholar.*') || 
            str_contains($request->path(), 'scholar')) {
            return true;
        }

        // Check if the user was previously authenticated as a scholar
        // by checking session data or referrer
        $referer = $request->header('referer');
        if ($referer && (str_contains($referer, 'scholar') || str_contains($referer, 'CLSU-ERDT-SCHOLARSHIP'))) {
            return true;
        }

        // Check if there's scholar guard information in session
        $lastGuard = session('auth.guard');
        if ($lastGuard === 'scholar') {
            return true;
        }

        return false;
    }
}