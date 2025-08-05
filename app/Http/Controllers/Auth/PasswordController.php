<?php

namespace App\Http\Controllers\Auth;

// Mga kailangan na klase para sa password controller
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * PasswordController - Nag-hahandle ng pagbabago ng password ng user
 * 
 * Ang controller na ito ay ginagamit kapag gusto ng user na palitan
 * ang kanyang kasalukuyang password sa sistema
 */
class PasswordController extends Controller
{
    /**
     * Nagbabago ng password ng user
     * 
     * Ginagamit ito kapag nag-submit ang user ng form para sa pagpalit ng password.
     * Kailangan niya i-provide ang kasalukuyang password at ang bagong password.
     * 
     * @param Request $request - Ang datos na galing sa form (kasama ang mga password)
     * @return RedirectResponse - Babalik sa dating page pagkatapos ng update
     */
    public function update(Request $request): RedirectResponse
    {
        // I-validate muna ang mga datos na na-submit ng user
        // Para masiguro na tama at kumpleto ang mga input
        $validated = $request->validate([
            // Kailangan i-type ang kasalukuyang password at dapat tama ito
            'current_password' => ['required', 'current_password'],
            // Ang bagong password ay kailangan:
            // - Hindi dapat walang laman (required)
            // - Dapat sumunod sa default na password rules (haba, complexity)
            // - Dapat pareho sa confirmation field (confirmed)
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Kapag na-validate na ang lahat, i-update na ang password ng user
        // Ginagamit ang Hash::make() para i-encrypt ang password bago i-save
        // Hindi kasi pwedeng i-save ang plain text na password sa database
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Pagkatapos ma-update ang password, ibabalik ang user sa dating page
        // Kasama na dito ang success message na makikita sa session
        return back();
    }
}
