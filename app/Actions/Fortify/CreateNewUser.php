<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'wallet-password' => ['required', 'string', 'max:255'],
            'refrence-code' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        $referenceUser = User::where('reference_code', $input['refrence-code'])->firstOrFail();

        $user = User::create([
                'name' => $input['name'],
                'phone' => $input['phone'],
                'vallet_password' => Hash::make($input['wallet-password']),
                'reference_code' => strtoupper(Str::random(6)),
                'password' => Hash::make($input['password']),
                'parent_id' => $referenceUser->id,
                'credibility' => 100,
                'user_type' => 0,
                'membership_level_id' => 1,
                'funds' => 0,
                'status' => 'active',
                'min_withdraw' => 50,
                'max_withdraw' => 500,
        ]);

        event(new Registered($user));

        return $user;
    }
}
