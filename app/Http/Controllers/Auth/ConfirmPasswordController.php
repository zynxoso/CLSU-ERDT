<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

/**
 * Controller para sa pag-confirm ng password ng user
 * 
 * Ginagamit ito kapag kailangan ng user na i-confirm ulit ang kanyang password
 * bago makagawa ng sensitive na aksyon tulad ng pag-delete ng account
 */
class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | Ang controller na ito ay nangangalaga sa pag-confirm ng password
    | at gumagamit ng trait para sa mga basic na functionality.
    | Pwede mong i-customize ang mga function kung kailangan.
    |
    */

    // Ginagamit ang ConfirmsPasswords trait na may mga built-in na methods
    // para sa password confirmation functionality
    use ConfirmsPasswords;

    /**
     * Kung saan ire-redirect ang user kapag hindi successful ang intended action
     * 
     * Kapag may error o hindi natapos ang gusto nilang gawin,
     * dito sila babalik sa /home page
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Ginagawa kapag ginawa ang bagong instance ng controller
     * 
     * Dito sinisiguro na ang user ay naka-login na bago makagamit
     * ng password confirmation features
     *
     * @return void
     */
    public function __construct()
    {
        // Kailangan naka-login ang user bago makagamit ng controller na ito
        // Kung hindi pa naka-login, ire-redirect sa login page
        $this->middleware('auth');
    }
}
