<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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
                'message' => "User creation failed"
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
            ], 204);
        }catch(Exception $e){
            return response()->json([
                'message' => "Unauthorized user"
            ], 401);
        }
    }
}
