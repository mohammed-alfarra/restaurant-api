<?php

namespace App\Actions\User;

use App\Http\Requests\User\RegisterUserRequest;
use App\Models\User;

class RegisterUser
{
    public function execute(RegisterUserRequest $request): User
    {
        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'password' => $request->get('password'),
        ]);

        return $user;
    }
}
