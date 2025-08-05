# Design Document

## Overview

The current authentication system has a critical security flaw where both admin and scholar users share the same `web` guard and session storage, leading to session persistence across different user roles. When a scholar logs out and an admin attempts to login, the residual session data can cause automatic authentication without proper credential verification.

This design implements proper authentication guard separation and comprehensive session cleanup to ensure complete isolation between different user roles and prevent unauthorized access.

## Architecture

### Current Issues
- Both admin and scholar authentication use the same `web` guard
- Logout methods don't properly clear all session data
- No guard-specific session isolation
- Middleware relies on shared authentication state
- Session invalidation is incomplete

### Proposed Solution
- Implement separate authentication guards for admin and scholar roles
- Create comprehensive session cleanup mechanisms
- Add guard-specific middleware validation
- Implement proper session token regeneration
- Add authentication state verification

## Components and Interfaces

### 1. Authentication Guard Configuration

**Modified Guards (`config/auth.php`)**
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'admin' => [
        'driver' => 'session', 
        'provider' => 'admins',
    ],
    'scholar' => [
        'driver' => 'session',
        'provider' => 'scholars',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'scholars' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

### 2. Enhanced Authentication Controllers

**AdminAuthenticatedSessionController**
- Use `admin` guard for authentication
- Validate user role before authentication
- Implement comprehensive logout with session cleanup
- Add session regeneration and token invalidation

**ScholarAuthenticatedSessionController** 
- Use `scholar` guard for authentication
- Validate user role before authentication
- Implement comprehensive logout with session cleanup
- Add session regeneration and token invalidation

### 3. Session Management Service

**SessionCleanupService**
```php
class SessionCleanupService
{
    public static function clearAllAuthenticationData(Request $request): void
    {
        // Clear all guard sessions
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout(); 
        Auth::guard('scholar')->logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear remember tokens
        if ($user = Auth::user()) {
            $user->remember_token = null;
            $user->save();
        }
    }
}
```

### 4. Enhanced Middleware

**AdminMiddleware**
- Check authentication against `admin` guard
- Validate user role is admin or super_admin
- Redirect to admin login on failure
- Add comprehensive logging

**ScholarMiddleware** (New)
- Check authentication against `scholar` guard  
- Validate user role is scholar
- Redirect to scholar login on failure
- Add comprehensive logging

### 5. Route Protection Updates

**Admin Routes**
```php
Route::middleware(['auth:admin', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin routes
    });
```

**Scholar Routes**
```php
Route::middleware(['auth:scholar', ScholarMiddleware::class])
    ->prefix('scholar')
    ->name('scholar.')
    ->group(function () {
        // Scholar routes
    });
```

## Data Models

### User Model Enhancements
- Add guard-specific authentication methods
- Implement role-based guard selection
- Add session cleanup helpers

### Session Storage
- Maintain existing database session storage
- Add guard-specific session identification
- Implement session cleanup for expired/invalid sessions

## Error Handling

### Authentication Failures
- Clear all authentication state on any authentication failure
- Redirect to appropriate login page based on attempted access
- Display clear error messages without revealing system details
- Log authentication attempts for security monitoring

### Session Validation Errors
- Detect and handle corrupted session data
- Force re-authentication on session inconsistencies
- Clear invalid session data automatically
- Prevent session fixation attacks

### Cross-Role Access Attempts
- Detect attempts to access wrong role areas
- Log security violations
- Force complete logout and re-authentication
- Display appropriate error messages

## Testing Strategy

### Unit Tests
- Test guard-specific authentication methods
- Test session cleanup functionality
- Test role validation logic
- Test middleware behavior with different user roles

### Integration Tests
- Test complete login/logout flows for each role
- Test cross-role access prevention
- Test session isolation between roles
- Test authentication state after logout

### Security Tests
- Test session fixation prevention
- Test unauthorized access attempts
- Test session cleanup completeness
- Test authentication bypass attempts

### End-to-End Tests
- Test scholar logout → admin login flow
- Test admin logout → scholar login flow
- Test concurrent sessions for different roles
- Test session timeout and cleanup

## Implementation Phases

### Phase 1: Guard Configuration
- Update authentication guard configuration
- Create separate providers for admin and scholar
- Test basic guard functionality

### Phase 2: Controller Updates
- Modify authentication controllers to use specific guards
- Implement comprehensive session cleanup
- Add proper role validation

### Phase 3: Middleware Enhancement
- Create ScholarMiddleware
- Update AdminMiddleware for guard-specific authentication
- Add comprehensive session validation

### Phase 4: Route Protection
- Update route definitions to use specific guards
- Test route protection with new middleware
- Verify proper redirections

### Phase 5: Testing and Validation
- Implement comprehensive test suite
- Perform security testing
- Validate session isolation
- Test all authentication flows