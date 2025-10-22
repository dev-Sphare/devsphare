<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HackathonController;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/hackathons', [HackathonController::class, 'index']);
    Route::get('/hackathons/{slug}', [HackathonController::class, 'show']);

    
    
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/hackathons', [HackathonController::class, 'store']);
        Route::patch('/hackathons/{hackathon}', [HackathonController::class, 'update']);
        Route::delete('/hackathons/{hackathon}', [HackathonController::class, 'destroy']);
    });


});
