<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User; // Ginagamit para sa User model

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller - Kontrolador para sa Pag-login
    |--------------------------------------------------------------------------
    |
    | Ang controller na ito ay nangangalaga sa pag-authenticate (pagpapatunay)
    | ng mga user sa application at nagre-redirect sa kanila sa tamang pahina
    | pagkatapos mag-login. Gumagamit ito ng trait para sa mga basic functions.
    |
    */

    // Ginagamit ang AuthenticatesUsers trait para sa mga basic login functions
    use AuthenticatesUsers;

    /**
     * Kung saan ire-redirect ang mga user pagkatapos mag-login
     * Default na destination ay '/home' page
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Constructor - Ginagawa kapag ginawa ang bagong instance ng controller
     * Dito natin nilalagay ang mga middleware (security filters)
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware na 'guest' - pwede lang gamitin ng mga hindi pa naka-login
        // Lahat ng functions pwede except 'logout'
        $this->middleware('guest')->except('logout');
        
        // Middleware na 'auth' - kailangan naka-login para gamitin
        // Ang 'logout' function lang ang kailangan nito
        $this->middleware('auth')->only('logout');
    }

    /**
     * Dito natin tinutukoy kung saan ire-redirect ang user pagkatapos mag-login
     * Depende sa role (tungkulin) ng user kung saan sila pupunta
     *
     * @return string - ang route kung saan pupunta ang user
     */
    protected function redirectTo()
    {
        // Kunin ang current user na naka-login
        // Tingnan kung scholar ba ang role niya
        if (Auth::user() && Auth::user()->role === 'scholar') {
            // Kung scholar, dalhin sa scholar dashboard
            return route('scholar.dashboard');
        }
        
        // Kung hindi scholar (admin o super_admin), dalhin sa admin dashboard
        return route('admin.dashboard');
    }

    /**
     * Pag-logout ng user sa application
     * Ginagawa kapag gusto ng user na mag-logout
     *
     * @param  \Illuminate\Http\Request  $request - ang HTTP request
     * @return \Illuminate\Http\RedirectResponse - redirect response
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        // Kunin ang current user bago mag-logout
        $user = Auth::user();
        
        // Tingnan kung admin ba ang user (admin o super_admin)
        $isAdmin = $user && in_array($user->role, ['admin', 'super_admin']);
        
        // I-logout ang user sa system
        $this->guard()->logout();

        // I-invalidate (gawing invalid) ang current session
        // Para hindi na magamit ang lumang session data
        session()->invalidate();
        
        // Gumawa ng bagong session token para sa security
        session()->regenerateToken();

        // Pumili ng tamang login page depende sa role ng user
        // Kung admin, sa regular login page. Kung scholar, sa scholar login page
        $loginRoute = $isAdmin ? 'login' : 'scholar-login';
        
        // I-redirect ang user sa tamang login page
        return redirect()->route($loginRoute);
    }
}
