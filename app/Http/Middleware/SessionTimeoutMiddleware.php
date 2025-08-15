<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\SessionManagementService;
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
        $sessionService = app(SessionManagementService::class);
        $isWebAuthenticated = Auth::guard('web')->check();
        $isScholarAuthenticated = Auth::guard('scholar')->check();
        
        // Check if any user is authenticated
        if ($isWebAuthenticated || $isScholarAuthenticated) {
            // Check if session is still valid
            if (!$sessionService->isSessionValid()) {
                // Handle session timeout using the service
                $timeoutResult = $sessionService->handleSessionTimeout($request);
                
                // Return appropriate response based on request type
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => $timeoutResult['message'],
                        'redirect' => route($timeoutResult['route'])
                    ], 401);
                }
                
                return redirect()->route($timeoutResult['route'])
                    ->with('error', $timeoutResult['message']);
            }
            
            // Update last activity timestamp
            $sessionService->updateLastActivity();
        }
        
        return $next($request);
    }
}
