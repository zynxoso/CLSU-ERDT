<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\DatabaseSecurityService;
use Illuminate\Support\Facades\Auth;

class DatabaseSecurityMiddleware
{
    protected DatabaseSecurityService $securityService;

    public function __construct(DatabaseSecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set database security context for authenticated users
        if (Auth::check()) {
            $user = Auth::user();

            // Set the security context
            $this->securityService->setSecurityContext($user);

            // Audit the security context setting
            $this->securityService->auditSecurityAction('context_set', [
                'route' => $request->route()->getName(),
                'method' => $request->method(),
                'url' => $request->url()
            ]);
        }

        $response = $next($request);

        // Reset security context after request completion
        if (Auth::check()) {
            $this->securityService->resetSecurityContext();
        }

        return $response;
    }
}
