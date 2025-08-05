<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller na nag-hahandle ng password confirmation
 * Ginagamit ito kapag kailangan ng user na i-confirm ulit ang kanyang password
 * para sa mga sensitive na operasyon tulad ng pag-delete ng account
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Ipapakita ang form para sa password confirmation
     * Ginagamit ito kapag kailangan ng user na i-type ulit ang password
     */
    public function show(): Response
    {
        // Magre-render ng ConfirmPassword page gamit ang Inertia
        // Ang Inertia ay nagco-connect ng Laravel backend sa frontend
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * I-confirm ang password ng user
     * Ito ang method na tumatanggap ng form submission mula sa password confirmation page
     */
    public function store(Request $request): RedirectResponse
    {
        // I-check kung tama ang password na ni-type ng user
        // Ginagamit ang email ng current user at yung password na ni-submit
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,    // Email ng naka-login na user
            'password' => $request->password,      // Password na ni-type sa form
        ])) {
            // Kung mali ang password, mag-throw ng error
            // Ang ValidationException ay magse-send ng error message pabalik sa form
            throw ValidationException::withMessages([
                'password' => __('auth.password'),  // Error message na "The password is incorrect"
            ]);
        }

        // Kung tama ang password, i-save sa session na na-confirm na ang password
        // Ang timestamp na ito ay ginagamit para malaman kung kailan huling na-confirm ang password
        session()->put('auth.password_confirmed_at', time());

        // I-redirect ang user sa dashboard o sa page na gusto niyang puntahan
        // Ang intended() ay bumabalik sa page na gusto niyang puntahan bago siya na-redirect dito
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
