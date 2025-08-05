<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * validation at pag-update ng password ng user
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        // validation ng input para sa pag-update ng password
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('updatePassword');

        // pag-update ng password sa database
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
