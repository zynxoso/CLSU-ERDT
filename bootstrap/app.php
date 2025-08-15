<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->append([
            \App\Http\Middleware\CyberSweepMiddleware::class,
            \App\Http\Middleware\ForceHttpsMiddleware::class,
        ]);

        // Web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\CheckPasswordExpiration::class,
            \App\Http\Middleware\SessionTimeoutMiddleware::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'password.expiry' => \App\Http\Middleware\CheckPasswordExpiration::class,
            'api.rate.limit' => \App\Http\Middleware\ApiRateLimitMiddleware::class,
            'db.security' => \App\Http\Middleware\DatabaseSecurityMiddleware::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeoutMiddleware::class,
            'error.handling' => \App\Http\Middleware\ErrorHandlingMiddleware::class,
            'db.error.handling' => \App\Http\Middleware\DatabaseErrorHandlingMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
