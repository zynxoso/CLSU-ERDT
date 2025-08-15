<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing user sessions and logout functionality
 * Provides centralized session management across all user roles
 */
class SessionManagementService
{
    /**
     * Perform a complete logout for any authenticated user
     * Handles role-based redirection and session cleanup
     *
     * @param Request $request
     * @return array Contains redirect route and message
     */
    public function performLogout(Request $request): array
    {
        // Determine user type and role before logout
        $userInfo = $this->getUserInfoBeforeLogout();
        
        // Log the logout attempt
        $this->logLogoutAttempt($userInfo);
        
        // Perform complete session cleanup
        $this->clearAllSessions($request);
        
        // Determine appropriate redirect route
        $redirectRoute = $this->determineRedirectRoute($userInfo);
        
        return [
            'route' => $redirectRoute,
            'message' => 'You have been successfully logged out.',
            'user_type' => $userInfo['type']
        ];
    }
    
    /**
     * Handle session timeout logout
     * Similar to regular logout but with timeout-specific messaging
     *
     * @param Request $request
     * @return array
     */
    public function handleSessionTimeout(Request $request): array
    {
        $userInfo = $this->getUserInfoBeforeLogout();
        
        // Log the timeout event
        Log::info('Session timeout occurred', [
            'user_id' => $userInfo['user_id'],
            'user_type' => $userInfo['type'],
            'role' => $userInfo['role'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        $this->clearAllSessions($request);
        $redirectRoute = $this->determineRedirectRoute($userInfo);
        
        return [
            'route' => $redirectRoute,
            'message' => 'Your session has expired due to inactivity. Please login again.',
            'user_type' => $userInfo['type']
        ];
    }
    
    /**
     * Get user information before logout
     *
     * @return array
     */
    private function getUserInfoBeforeLogout(): array
    {
        $userInfo = [
            'type' => 'guest',
            'role' => null,
            'user_id' => null,
            'is_scholar' => false,
            'is_admin' => false
        ];
        
        // Check web guard (admin/super_admin)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $userInfo = [
                'type' => 'web',
                'role' => $user->role,
                'user_id' => $user->id,
                'is_scholar' => false,
                'is_admin' => in_array($user->role, ['admin', 'super_admin'])
            ];
        }
        // Check scholar guard
        elseif (Auth::guard('scholar')->check()) {
            $user = Auth::guard('scholar')->user();
            $userInfo = [
                'type' => 'scholar',
                'role' => $user->role,
                'user_id' => $user->id,
                'is_scholar' => true,
                'is_admin' => false
            ];
        }
        
        return $userInfo;
    }
    
    /**
     * Clear all authentication sessions and session data
     *
     * @param Request $request
     */
    private function clearAllSessions(Request $request): void
    {
        // Logout from all guards
        Auth::guard('web')->logout();
        Auth::guard('scholar')->logout();
        
        // Clear session data
        Session::invalidate();
        Session::regenerateToken();
        Session::flush();
        
        // Clear specific session keys
        $request->session()->forget([
            'auth.guard',
            'last_activity',
            'show_password_modal',
            'user_preferences',
            'temp_data'
        ]);
    }
    
    /**
     * Determine the appropriate redirect route based on user type
     *
     * @param array $userInfo
     * @return string
     */
    private function determineRedirectRoute(array $userInfo): string
    {
        // Scholar users always go to scholar login
        if ($userInfo['is_scholar'] || $userInfo['role'] === 'scholar') {
            return 'scholar.login';
        }
        
        // Admin and super_admin go to general login
        if ($userInfo['is_admin']) {
            return 'login';
        }
        
        // Default to general login for unknown users
        return 'login';
    }
    
    /**
     * Log the logout attempt for security auditing
     *
     * @param array $userInfo
     */
    private function logLogoutAttempt(array $userInfo): void
    {
        if ($userInfo['user_id']) {
            Log::info('User logout', [
                'user_id' => $userInfo['user_id'],
                'user_type' => $userInfo['type'],
                'role' => $userInfo['role'],
                'timestamp' => now(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }
    }
    
    /**
     * Check if a user session is still valid
     *
     * @return bool
     */
    public function isSessionValid(): bool
    {
        $lastActivity = Session::get('last_activity');
        $sessionLifetime = config('session.lifetime') * 60; // Convert to seconds
        
        if (!$lastActivity) {
            return true; // No last activity recorded, assume valid
        }
        
        return (time() - $lastActivity) <= $sessionLifetime;
    }
    
    /**
     * Update the last activity timestamp
     */
    public function updateLastActivity(): void
    {
        Session::put('last_activity', time());
    }
    
    /**
     * Get session status information
     *
     * @return array
     */
    public function getSessionStatus(): array
    {
        $isWebAuth = Auth::guard('web')->check();
        $isScholarAuth = Auth::guard('scholar')->check();
        
        return [
            'authenticated' => $isWebAuth || $isScholarAuth,
            'guard' => $isWebAuth ? 'web' : ($isScholarAuth ? 'scholar' : null),
            'last_activity' => Session::get('last_activity', time()),
            'session_lifetime' => config('session.lifetime'),
            'is_valid' => $this->isSessionValid()
        ];
    }
}