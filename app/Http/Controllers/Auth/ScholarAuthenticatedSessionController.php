<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScholarAuthenticatedSessionController extends Controller
{
    /**
     * Display the scholar login view.
     */
    public function create(): View
    {
        return view('auth.scholar-login');
    }

    /**
     * Handle an incoming scholar authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('scholar');

        $request->session()->regenerate();

        return redirect()->intended(route('scholar.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated scholar session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('scholar')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('scholar-login');
    }
}