<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\Auth\LoginRegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    // use PasswordValidationRules; // 既定ルールではなく独自ルールを使用

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $validator = Validator::make(
            $input,
            LoginRegisterRequest::registerRules(),
            LoginRegisterRequest::messageMap()
        );
        if ($validator->fails()) {
            throw (new \Illuminate\Validation\ValidationException($validator))->errorBag('register');
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
