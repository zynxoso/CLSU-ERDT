# CLSU-ERDT Authentication System

This document provides a comprehensive explanation of the authentication system implemented in the CLSU-ERDT Scholar Management System.

## Authentication Story Timeline

The authentication system in CLSU-ERDT follows this chronological journey:

1. **Initial Setup (Development Phase)**
   - Laravel's authentication scaffolding is installed using Laravel Fortify
   - User roles ('admin' and 'scholar') are defined in the database migration
   - Custom middleware for role-based protection is created
   - Routes are organized into role-specific groups with appropriate protection
   - The system is configured to use MySQL for data persistence

2. **User Creation (Admin-Only)**
   - Only administrators can create new user accounts
   - Admin creates scholar accounts through the admin interface
   - Scholars cannot self-register; this is a deliberate security decision
   - The system uses Laravel's built-in password hashing via the `Hash` facade
   - Email verification is enabled but optional

3. **Login Process (User Authentication)**
   - User navigates to the login page
   - Laravel's authentication system processes login requests
   - The system uses traditional cookie-based authentication with session storage
   - Credentials are validated against the database records
   - Failed login attempts are rate-limited to prevent brute force attacks
   - Successful login regenerates the session for security

4. **Session Management (During Active Use)**
   - Laravel manages the user session with secure cookie settings
   - CSRF protection is enforced on all forms
   - The system uses Laravel's middleware system to protect routes
   - Role-specific functionality is protected by custom middleware
   - Laravel's authentication guards maintain the authentication state

5. **Access Control (During Application Use)**
   - Every request passes through appropriate middleware
   - Access is determined by the user's role stored in the database
   - Admin routes are protected by the `AdminMiddleware`
   - Scholar routes check for the appropriate user role
   - The system leverages Laravel's policy-based authorization for fine-grained control

6. **Audit Logging (Throughout User Journey)**
   - All significant authentication events are logged (login, logout, password changes)
   - The custom `AuditService` tracks user actions with timestamps and IP addresses
   - This creates a chronological record of all authentication activities
   - Logs are stored in the database for easy searching and reporting

7. **Security Measures (Continuous Protection)**
   - Passwords are hashed using bcrypt (Laravel's default)
   - Sessions are secured against fixation attacks
   - Input validation prevents malicious data entry
   - SQL injection is prevented through Laravel's query builder
   - XSS protection is applied to all output

8. **Password Management (As Needed)**
   - Users can request password resets via email
   - The system uses Laravel's built-in password reset functionality
   - Tokens are time-limited for security
   - Password strength requirements enforce secure passwords

9. **Logout Process (End of Session)**
   - User sessions are invalidated on logout
   - Session tokens are regenerated
   - The user is redirected to the login page
   - Audit logs record the logout event

This authentication journey leverages Laravel's robust security features while extending them with custom middleware and role-based protections specific to the CLSU-ERDT Scholar Management System.

## Technologies At A Glance

The CLSU-ERDT authentication system is built using:

- **Laravel 10.x**: Core PHP framework providing the foundation for authentication
- **Laravel Fortify**: Authentication backend service provider
- **Bcrypt Hashing**: Secure password hashing algorithm
- **MySQL Database**: Storage for user credentials and authentication data
- **PHP Session Management**: Cookie-based session handling
- **Role-Based Middleware**: Custom middleware for securing routes by user role
- **CSRF Protection**: Cross-Site Request Forgery prevention tokens
- **Rate Limiting**: Throttling mechanism to prevent brute force attacks
- **Email Verification**: Optional verification of new accounts
- **Blade/React Templates**: Frontend components that adapt based on authentication state
- **Custom AuditService**: Authentication event logging system
- **MySQL Transactions**: Ensuring data integrity during authentication processes
- **Laravel Validation**: Input validation for secure form processing

All authentication components work together to create a cohesive, secure system that enforces appropriate access controls while providing a smooth user experience.

## 5W1H of the Authentication System

### Who?

- **Users**: The system has two primary user roles:
  - **Admins**: Administrators responsible for managing scholars, approving fund requests, and overseeing the entire system
  - **Scholars**: Users who submit documents, make fund requests, and track their academic progress

- **Developers**: The authentication system is designed for developers to easily implement role-specific access controls

### What?

The CLSU-ERDT Scholar Management System implements a comprehensive authentication system that includes:

- User registration (admin-only privilege)
- Login/logout functionality
- Role-based access control (RBAC)
- Password reset and recovery
- Email verification
- Session management
- Secure routing and middleware protection
- Activity audit logging

### When?

- Authentication is enforced throughout the application lifecycle:
  - During initial access to the system
  - When accessing role-specific routes
  - When performing privileged actions (approving/rejecting requests, uploading documents)
  - After session timeout or manual logout
  - When changing passwords or updating account details

### Where?

- Authentication is applied across the entire application:
  - Web routes protected by middleware
  - Controller methods with role-specific checks
  - Views with conditional rendering based on user roles
  - API endpoints (if applicable)
  - System features with specific access control requirements

### Why?

The authentication system was implemented to:

- Secure sensitive scholar and financial information
- Enforce proper access controls based on user roles
- Prevent unauthorized access to administrative functions
- Maintain data integrity through proper authorization
- Track user actions for audit purposes
- Comply with data protection and privacy requirements
- Provide tailored experiences based on user roles

### How?

The authentication system works through the following mechanisms:

1. **Login Process**:
   - Users enter credentials (email and password)
   - The system validates credentials against the stored user records
   - If valid, a session is created and the user is redirected to the appropriate dashboard based on role
   - If invalid, error messages are displayed

2. **Middleware Protection**:
   - Routes are protected by middleware (`auth`, `admin`, etc.)
   - Middleware checks for authentication and proper role authorization
   - Unauthorized access attempts are redirected or denied with HTTP 403 errors

3. **Role-Based Access Control**:
   - User roles are stored in the database ('admin' or 'scholar')
   - Specific controllers and routes check the user role
   - The UI adapts based on role (showing/hiding features)
   - Admin-specific routes are completely protected from scholar access

4. **Session Management**:
   - Laravel's built-in session management handles user sessions
   - Sessions are secured with proper CSRF protection
   - Idle timeout protection is implemented

5. **Registration**:
   - User registration is restricted to administrators only
   - Admins can create both admin and scholar accounts
   - Scholars cannot register themselves

## Authentication Flow Diagram

```
┌─────────────┐     ┌────────────┐     ┌───────────────┐     ┌────────────┐
│             │     │            │     │               │     │            │
│   Login     │────▶│  Validate  │────▶│  Create       │────▶│  Redirect  │
│   Request   │     │  Credentials│     │  Session      │     │  to Role   │
│             │     │            │     │               │     │  Dashboard  │
└─────────────┘     └────────────┘     └───────────────┘     └────────────┘
       │                  │                                        ▲
       │                  │                                        │
       │                  ▼                                        │
       │            ┌────────────┐                         ┌──────────────┐
       │            │            │                         │              │
       └───────────▶│  Display   │                         │  Check User  │
                    │  Error     │                         │  Role        │
                    │            │                         │              │
                    └────────────┘                         └──────────────┘
```

## Implementation Details

### User Model Structure

The `User` model is the core of the authentication system and has the following structure:

```php
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships and methods...
}
```

### Database Schema

The users table has the following structure in the migration file:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['admin', 'scholar'])->default('scholar');
    $table->boolean('is_active')->default(true);
    $table->rememberToken();
    $table->timestamps();
});
```

### Admin Middleware Implementation

The `AdminMiddleware` class ensures that only administrators can access protected routes:

```php
public function handle(Request $request, Closure $next): Response
{
    // Check authentication
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('error', 'Please log in to access this page.');
    }

    // Check admin role
    if (Auth::user()->role !== 'admin') {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access the admin area.');
    }

    return $next($request);
}
```

### Login Process Detail

1. User submits credentials through the login form
2. The `AuthenticatedSessionController` handles the request:

```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
}
```

3. The `LoginRequest` authenticates the credentials with rate limiting:

```php
public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}
```

4. The `LoginController` redirects based on user role:

```php
protected function redirectTo()
{
    if (Auth::user() && Auth::user()->role === 'scholar') {
        return route('scholar.dashboard');
    }
    
    return route('admin.dashboard');
}
```

### Route Protection Strategies

#### Scholar Routes

Scholar-specific routes are protected like this:

```php
// Scholar routes - only accessible to scholars
Route::middleware(['auth'])->prefix('scholar')->name('scholar.')->group(function () {
    Route::get('/dashboard', [ScholarController::class, 'dashboard'])->name('dashboard');
    
    // Additional scholar routes...
});
```

Additional protection is implemented in the `ScholarController`:

```php
public function __construct()
{
    $this->middleware('auth');

    // Only restrict scholar-specific methods
    $this->middleware(function ($request, $next) {
        // For scholar-specific routes, redirect admins to admin dashboard
        if ($request->route()->getName() == 'scholar.dashboard') {
            if (Auth::user()->role !== 'scholar') {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    });
}
```

#### Admin Routes

Admin routes are protected with multiple middlewares:

```php
// Admin routes - only accessible to admins
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Additional admin routes...
    });
```

### Role-Based Authorization

Role checking is performed in several places:

1. Using direct role checks:

```php
if (Auth::user()->role === 'admin') {
    // Admin-specific code
}
```

2. Using the `CheckRole` middleware:

```php
class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

3. Using the helper methods in the User model:

```php
public function isAdmin()
{
    return !$this->isScholar();
}

public function isScholar()
{
    return $this->scholarProfile !== null;
}
```

### Password Reset Flow

1. User requests password reset from the login page
2. System sends a reset link to the user's email
3. User clicks the link and is presented with a password reset form
4. User enters a new password
5. Password is updated and user is redirected to login

This is handled by the following controllers:
- `PasswordResetLinkController`
- `NewPasswordController`

### Security Measures in Detail

#### Password Hashing

All passwords are automatically hashed using Laravel's `Hash` facade with bcrypt:

```php
protected $casts = [
    'password' => 'hashed',
];
```

#### Rate Limiting for Login Attempts

Rate limiting is implemented to prevent brute force attacks:

```php
public function ensureIsNotRateLimited(): void
{
    if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }

    event(new Lockout($this));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
        'email' => trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]),
    ]);
}
```

#### Input Validation

All input is validated using Laravel's validation rules:

```php
$request->validate([
    'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
]);
```

#### Session Security

Sessions are regenerated on login and logout to prevent session fixation attacks:

```php
// On login
$request->session()->regenerate();

// On logout
$request->session()->invalidate();
$request->session()->regenerateToken();
```

#### CSRF Protection

All forms include CSRF protection tokens, and the application verifies them on submission:

```html
@csrf
```

### Audit Logging

Authentication events are logged using the custom `AuditService`:

```php
$this->auditService->log(
    'login',
    'User',
    Auth::id(),
    ['ip' => $request->ip(), 'user_agent' => $request->userAgent()]
);
```

## Technologies Used

The authentication system leverages the following components:

1. **Laravel Framework**: Core authentication foundation with middleware support
2. **Laravel Fortify**: Provides authentication backend services including:
   - Login and logout
   - Password reset
   - Email verification
   - Two-factor authentication (configured but optional)

3. **Database**: MySQL for storing user credentials and profile information
4. **Middlewares**: Custom middlewares including:
   - `AdminMiddleware`: Ensures only admins can access admin routes
   - `CheckRole`: Generic role-checking middleware for versatile protection
   - `RedirectIfAuthenticated`: Handles appropriate redirection for authenticated users

5. **Controllers**:
   - `AuthenticatedSessionController`: Handles login/logout functionality
   - `
