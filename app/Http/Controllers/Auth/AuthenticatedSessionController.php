<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Controller na nag-hahandle ng pag-login at pag-logout ng mga admin users
 * Ginagamit ito para sa authentication ng mga admin at super admin
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Ipapakita ang login page para sa mga admin
     * Ginagamit ito kapag pumunta ang user sa /login
     */
    public function create(): View
    {
        // Magbabalik ng login view kasama ang mga data na kailangan
        return view('auth.login', [
            // Kunin ang status message kung meron (galing sa redirect)
            'status' => session('status'),
        ]);
    }

    /**
     * Ginagamit ito kapag nag-submit ng login form ang user
     * Dito nangyayari ang actual na pag-login process
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Una, linisin muna lahat ng dating login sessions para sa security
        Auth::guard('web')->logout();      // I-logout ang admin guard
        Auth::guard('scholar')->logout();  // I-logout din ang scholar guard
        session()->invalidate();           // Burahin ang lahat ng session data
        session()->regenerateToken();      // Gumawa ng bagong CSRF token
        
        // Gumawa ng bagong session para sa admin login
        session()->regenerate();
        
        // Subukan mag-login gamit ang web guard (para sa admin/super_admin)
        $request->authenticate();

        // Kunin ang user na nag-login
        $user = Auth::guard('web')->user();

        // Tingnan kung admin o super_admin ba ang nag-login
        if (in_array($user->role, ['admin', 'super_admin'])) {
            // Kung admin/super_admin, dalhin sa admin dashboard
            return redirect()->intended(route('admin.dashboard'));
        } else {
            // Kung hindi admin/super_admin, i-logout at dalhin sa scholar login
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('scholar-login')->withErrors([
                'email' => 'Wala kang admin access. Gamitin ang scholar login.',
            ]);
        }
    }

    /**
     * Ginagamit ito kapag nag-logout ang user
     * Lilinisin lahat ng session data para sa security
     */
    public function destroy(Request $request): RedirectResponse
    {
        // I-logout ang user sa web guard (admin guard)
        Auth::guard('web')->logout();
        
        // I-logout din sa scholar guard para siguradong malinis ang session
        Auth::guard('scholar')->logout();

        // Burahin lahat ng session data
        session()->invalidate();
        
        // Gumawa ng bagong CSRF token para sa security
        session()->regenerateToken();
        
        // Burahin din ang "remember me" cookies kung meron
        session()->flush();

        // Balik sa login page pagkatapos mag-logout
        return redirect()->route('login');
    }
}
