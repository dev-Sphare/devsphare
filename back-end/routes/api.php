<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HackathonController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RegistrationController;

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
      
        Route::post('/hackathons/{id}/register', [RegistrationController::class, 'store']);
        Route::get('/hackathons/{id}/registrations', [RegistrationController::class, 'index']);
    });

//on ur next work
//1, test the roots hafe work half dosent

//2, then Phase 2) will introduce:
// Teams (create/join/invite)
// Submissions (project uploads)
// Pre-signed file uploads (S3 or local)
});
