<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Exception;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service){}

    public function register(RegisterRequest $request){
        try{
            $created_user = $this->service->register($request->validated());
            return response()->json([
                "user" => $created_user,
                "message" => "success"
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'message' => "User creation failed"
            ], 500);
        }
    }
}
