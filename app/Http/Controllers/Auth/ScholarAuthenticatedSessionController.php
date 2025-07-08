<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ScholarAuthenticatedSessionController extends Controller
{
    /**
     * Display the scholar login view.
     */
    public function create(): View
    {
        // Get active announcements for display
        $announcements = Announcement::active()
            ->published()
            ->orderByPriority()
            ->limit(4)
            ->get();

        return view('auth.scholar-login', compact('announcements'));
    }

    /**
     * Handle an incoming scholar authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First, check if the user exists and get their role
        $user = User::where('email', $request->email)->first();

        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Double-check after successful authentication
        $authenticatedUser = Auth::user();
        if (in_array($authenticatedUser->role, ['admin', 'super_admin'])) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Check if user has default password and set modal flag
        if ($authenticatedUser->is_default_password) {
            session(['show_password_modal' => true]);
            session()->flash('info', 'Welcome! For your security, we recommend changing your default password.');
        }

        return redirect()->intended(route('scholar.dashboard'));
    }

    /**
     * Destroy an authenticated scholar session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('scholar-login');
    }
}
