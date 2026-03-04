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

    public function login(Array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new \Exception('Invalid credentials');
        }

        $token = $user->createToken('backend_api')->accessToken;

        return [
            "user" => $user,
            "access_token" => $token
        ];
    }

    public function logout(User $authorized_user){
        $authorized_user->token()->revoke();
    }

}