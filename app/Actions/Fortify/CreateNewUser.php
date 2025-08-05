<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validation at pag gawa ng bagong user account.
     * Para sa secure na pag register ng mga bagong users.
     *
     * @param  array<string, string>  $input  Data mula sa registration form
     * @return User  Yung bagong user na na-create
     */
    public function create(array $input): User
    {
        // Validation ng mga input data para sure na tama yung format
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'], // Validation ng pangalan - required, text, max 255 chars
            'email' => [
                'required',    // Validation na may email
                'string',      // Validation na text format
                'email',       // Validation ng email format
                'max:255',     // Validation ng length max 255 chars
                Rule::unique(User::class), // Validation na unique sa lahat ng users
            ],
            'password' => $this->passwordRules(), // Validation ng password rules
        ])->validate();

        // Pag gawa ng bagong user record sa database
        return User::create([
            'name' => $input['name'],                    // Pag save ng pangalan
            'email' => $input['email'],                  // Pag save ng email
            'password' => Hash::make($input['password']), // Pag encrypt ng password para secure
        ]);
    }
}
