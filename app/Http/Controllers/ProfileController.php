<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Get the authenticated user and update with validated data
        $user = Auth::user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Get the authenticated user and determine role
        $user = $request->user();
        $isAdmin = $user && in_array($user->role, ['admin', 'super_admin']);

        Auth::logout();
        $user->delete();

        // Clear session data
        session()->invalidate();
        session()->regenerateToken();

        // Redirect to appropriate login page based on user role
        $loginRoute = $isAdmin ? 'login' : 'scholar.login';
        return redirect()->route($loginRoute);
    }
}
