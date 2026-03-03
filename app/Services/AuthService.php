<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService{
    public function register(Array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create([
            ...$data
        ]);

        $token = $user->createToken('backend_api')->accessToken;
        
        return [
            "user" => $user,
            "access_token" => $token
        ];
    }

}