<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Vérifier si l'utilisateur a le token passport et si son mail est vérifié
Route::middleware(['auth:api', 'verified'])
    ->group(function(){
        Route::post('/logout', [AuthController::class, 'logout']);
});

// Cette route est un "placeholder" pour que Laravel puisse générer l'URL du mail
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');