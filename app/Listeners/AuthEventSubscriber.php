<?php

namespace App\Listeners;

use App\Services\AuditService;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class AuthEventSubscriber
{
    /**
     * The audit service instance.
     *
     * @var \App\Services\AuditService
     */
    protected $auditService;

    /**
     * Create a new event subscriber instance.
     *
     * @param \App\Services\AuditService $auditService
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Handle user login events.
     *
     * @param \Illuminate\Auth\Events\Login $event
     * @return void
     */
    public function handleUserLogin(Login $event)
    {
        $this->auditService->logCustomAction(
            'login',
            'User',
            $event->user->id,
            [
                'email' => $event->user->email,
                'role' => $event->user->role ?? 'unknown',
                'guard' => $event->guard,
            ]
        );

        // Update the user's last login information
        try {
            $userId = $event->user->id;
            User::where('id', $userId)->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't disrupt the login process
            Log::error('Failed to update login timestamp: ' . $e->getMessage());
        }
    }

    /**
     * Handle user logout events.
     *
     * @param \Illuminate\Auth\Events\Logout $event
     * @return void
     */
    public function handleUserLogout(Logout $event)
    {
        if ($event->user) {
            $this->auditService->logCustomAction(
                'logout',
                'User',
                $event->user->id,
                [
                    'email' => $event->user->email,
                    'role' => $event->user->role,
                    'guard' => $event->guard,
                ]
            );
        }
    }

    /**
     * Handle failed login attempts.
     *
     * @param \Illuminate\Auth\Events\Failed $event
     * @return void
     */
    public function handleFailedLogin(Failed $event)
    {
        $context = [
            'email' => request()->input('email'),
            'guard' => $event->guard,
            'ip_address' => request()->ip(),
        ];

        if ($event->user) {
            $this->auditService->logCustomAction(
                'failed_login',
                'User',
                $event->user->id,
                $context
            );
        } else {
            // If user doesn't exist, still log the attempt
            $this->auditService->logCustomAction(
                'failed_login',
                'User',
                null,
                $context
            );
        }
    }

    /**
     * Handle password reset events.
     *
     * @param \Illuminate\Auth\Events\PasswordReset $event
     * @return void
     */
    public function handlePasswordReset(PasswordReset $event)
    {
        $this->auditService->logCustomAction(
            'password_reset',
            'User',
            $event->user->id,
            [
                'email' => $event->user->email,
                'ip_address' => request()->ip(),
            ]
        );
    }

    /**
     * Handle user lockout events.
     *
     * @param \Illuminate\Auth\Events\Lockout $event
     * @return void
     */
    public function handleLockout(Lockout $event)
    {
        $this->auditService->logCustomAction(
            'account_lockout',
            'User',
            null,
            [
                'email' => request()->input('email'),
                'ip_address' => request()->ip(),
            ]
        );
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            Login::class,
            [AuthEventSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [AuthEventSubscriber::class, 'handleUserLogout']
        );

        $events->listen(
            Failed::class,
            [AuthEventSubscriber::class, 'handleFailedLogin']
        );

        $events->listen(
            PasswordReset::class,
            [AuthEventSubscriber::class, 'handlePasswordReset']
        );

        $events->listen(
            Lockout::class,
            [AuthEventSubscriber::class, 'handleLockout']
        );
    }
} 