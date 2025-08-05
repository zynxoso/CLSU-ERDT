<?php

namespace App\Http\Controllers\Admin;

// Mga kailangan na klase para sa controller na ito
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

/**
 * UserController - Namamahala sa lahat ng operasyon para sa mga user
 * Ginagamit ito ng mga admin para mag-manage ng mga user accounts
 */
class UserController extends Controller
{
    /**
     * Ipapakita ang listahan ng lahat ng users
     * Ginagamit ito kapag pumunta ang admin sa users page
     */
    public function index(): Response
    {
        // Kunin lahat ng users mula sa database
        $users = User::all();
        
        // Ibalik ang Inertia page kasama ang data ng users
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Ipapakita ang form para sa paggawa ng bagong user
     * Ginagamit ito kapag nag-click ang admin sa "Create User" button
     */
    public function create(): Response
    {
        // Ibalik ang create user form page
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * I-save ang bagong user sa database
     * Ginagamit ito kapag nag-submit ang admin ng create user form
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // I-validate muna ang mga input bago i-save
        $request->validate([
            'name' => 'required|string|max:255',        // Pangalan - required, string, max 255 characters
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class, // Email - required, valid email format, unique
            'password' => ['required', 'confirmed', Rules\Password::defaults()],       // Password - required, may confirmation, sundin ang default rules
            'role' => 'required|string|in:admin,scholar',  // Role - required, dapat admin o scholar lang
        ]);

        // Gumawa ng bagong user record sa database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // I-hash ang password para sa security
            'role' => $request->role,
            'is_default_password' => true,                 // Markahan na default password pa ito
            'must_change_password' => true,                // Kailangan mag-change ng password sa first login
        ]);

        // I-trigger ang registered event para sa mga listeners
        event(new Registered($user));

        // Bumalik sa users list page kasama ang success message
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Ipapakita ang form para sa pag-edit ng user
     * Ginagamit ito kapag nag-click ang admin sa "Edit" button sa isang user
     */
    public function edit(User $user): Response
    {
        // Ibalik ang edit form kasama ang data ng user na ie-edit
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * I-update ang impormasyon ng user
     * Ginagamit ito kapag nag-submit ang admin ng edit user form
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // I-validate ang mga input, pero exclude ang current user sa email uniqueness check
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|in:admin,scholar',
        ]);

        // Kung may bagong password na na-input
        if ($request->filled('password')) {
            // I-validate ang password
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);

            // I-hash at i-save ang bagong password
            $user->password = Hash::make($request->password);
        }

        // I-update ang mga basic information ng user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        // I-save ang mga changes sa database
        $user->save();

        // Bumalik sa users list page kasama ang success message
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Tanggalin ang user mula sa database
     * Ginagamit ito kapag nag-click ang admin sa "Delete" button
     */
    public function destroy(User $user): RedirectResponse
    {
        // I-delete ang user mula sa database
        $user->delete();
        
        // Bumalik sa users list page kasama ang success message
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    /**
     * I-toggle ang active status ng user (activate o deactivate)
     * Ginagamit ito kapag nag-click ang admin sa "Activate/Deactivate" button
     */
    public function toggle(User $user): RedirectResponse
    {
        // I-reverse ang current status - kung active, gawing inactive at vice versa
        $user->is_active = !$user->is_active;
        
        // I-save ang bagong status sa database
        $user->save();

        // Gumawa ng appropriate message depende sa bagong status
        $status = $user->is_active ? 'activated' : 'deactivated';

        // Bumalik sa previous page kasama ang success message
        return redirect()->back()->with('success', "User {$user->name} has been {$status} successfully");
    }
}
