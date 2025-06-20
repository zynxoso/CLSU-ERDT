<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(?string $guard = null): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        $remember = $this->boolean('remember');

        if ($guard === 'scholar') {
            if (! Auth::guard('scholar')->attempt($credentials, $remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
            $user = Auth::guard('scholar')->user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::guard('scholar')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated. Please contact the administrator.',
                ]);
            }

            if ($user->role !== 'scholar') {
                Auth::guard('scholar')->logout();
                throw ValidationException::withMessages([
                    'email' => 'You do not have scholar access.',
                ]);
            }
        } else {
            // Default to web guard for admin/super_admin
            if (! Auth::guard('web')->attempt($credentials, $remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
            $user = Auth::guard('web')->user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::guard('web')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated. Please contact the administrator.',
                ]);
            }

            if ($user->role === 'scholar') {
                Auth::guard('web')->logout();
                throw ValidationException::withMessages([
                    'email' => 'You do not have admin/super admin access.',
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
