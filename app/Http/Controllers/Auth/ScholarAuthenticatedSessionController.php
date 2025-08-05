<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller para sa pag-handle ng login at logout ng mga scholar
 * Ginagamit ito para sa authentication ng mga estudyanteng may scholarship
 */
class ScholarAuthenticatedSessionController extends Controller
{
    /**
     * Ipapakita ang login page para sa mga scholar
     * Dito makikita ng mga estudyante ang form para mag-login
     */
    public function create(): View
    {
        // Kunin ang mga aktibong announcement na ipapakita sa login page
        // Limitahan sa 4 lang para hindi masyadong marami
        $announcements = Announcement::active()
            ->published()
            ->orderByPriority()
            ->limit(4)
            ->get();

        // Ibalik ang login view kasama ang mga announcement
        return view('auth.scholar-login', compact('announcements'));
    }

    /**
     * Ginagamit kapag nag-submit ng login form ang scholar
     * Dito natin iche-check kung tama ang username at password
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Una, i-clear muna lahat ng dating login sessions
        // Para siguradong walang conflict sa bagong login
        Auth::guard('web')->logout();
        Auth::guard('scholar')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        // Gumawa ng bagong session para sa scholar
        session()->regenerate();
        
        // I-authenticate ang user gamit ang scholar guard
        // Dito natin iche-check kung tama ang login credentials
        $request->authenticate('scholar');
        
        // Kunin ang user na nag-login
        $authenticatedUser = Auth::guard('scholar')->user();
        
        // Tingnan kung default password pa ang ginagamit
        // Kung oo, ipapakita natin ang modal para mag-change password
        if ($authenticatedUser->is_default_password) {
            session(['show_password_modal' => true]);
            session()->flash('info', 'Welcome! For your security, we recommend changing your default password.');
        }

        // I-redirect ang user sa scholar dashboard
        return redirect()->intended(route('scholar.dashboard'));
    }

    /**
     * Ginagamit kapag nag-logout ang scholar
     * Dito natin i-clear lahat ng session data para secure
     */
    public function destroy(Request $request): RedirectResponse
    {
        // I-logout ang scholar sa scholar guard
        Auth::guard('scholar')->logout();
        
        // I-logout din sa web guard para siguradong walang natira
        Auth::guard('web')->logout();

        // I-clear lahat ng session data
        session()->invalidate();
        session()->regenerateToken();
        
        // I-clear din ang mga remember me cookies
        session()->flush();

        // Ibalik sa login page ang user
        return redirect()->route('scholar-login');
    }
}
