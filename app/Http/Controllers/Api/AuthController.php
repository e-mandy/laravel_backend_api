<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service){}

    public function register(RegisterRequest $request){
        try{
            $response = $this->service->register($request->validated());
            return response()->json([
                "message" => "success",
                "data" => $response
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request){
        try{
            $response = $this->service->login($request->validated());
            return response()->json([
                'message' => "success",
                "data" => $response
            ]);
        }catch(Exception $e){
                return response()->json([
                    'message' => $e->getMessage()
                ], $e->getMessage() === "Invalid credentials" ? 401 : 500);
        }
    }

    public function logout(Request $request){
        try{
            $this->service->logout($request->user());
            return response()->json([
                'message' => "User logged out successfully",
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => "Unauthorized user"
            ], 401);
        }
    }

    public function verify(Request $request, $id, $hash){
        $user = User::findOrFail($id);

        if(!hash_equals((string) $hash, sha1($user->getEmailForVerification()))){
            return response()->json([
                "message" => "Invalid link"
            ], 403);
        }

        // Vérifier si le user était déjà vérifié
        if($user->hasVerifiedEmail()){
            return response()->json([
                "message" => "Email already verified"
            ]);
        }

        if($user->markEmailAsVerified()){
            return response()->json([
                "message" => "Email verified successfully"
            ], 200);
        }
    }
}