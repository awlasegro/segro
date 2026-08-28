<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
    // Validate the input data
    Validator::make($input, [
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:255', 'unique:users'],
        'wallet-password' => ['required', 'string', 'max:255'],
        'refrence-code' => ['required', 'string', 'max:255'],
        'password' => $this->passwordRules(),
    ])->validate();

    // Find the user with the provided reference code
    $referrer = User::where('refrence-code', $input['refrence-code'])->first();

    // If the reference code is invalid (not found in the users table)
    if (!$referrer) {
        throw ValidationException::withMessages([
            'refrence-code' => 'Invalid reference code provided.',
        ]);
    }

    // Create the new user with additional fields
    return User::create([
        'name' => $input['name'],
        'phone' => $input['phone'],
        'vallet-password' => Hash::make($input['wallet-password']), // Make sure to hash if needed
        'refrence-code' => $input['refrence-code'],
        'password' => Hash::make($input['password']),
        'parent_id' => $referrer->id, // Set the parent_id to the referrer's ID
        'credibility' => 100, // Set the default credibility
        'user_type' => 0, // Set the default user type
        'membership_level_id' => 1, // Set the default membership level ID
        'funds' => 0, // Set the default funds
        'status' => 'active', // Set the default status
        'min_withdraw' => 50, // Set the minimum withdrawal amount
        'max_withdraw' => 500, // Set the maximum withdrawal amount
    ]);
    event(new Registered($user));
}
}
