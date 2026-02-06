<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ConnectionController;

Route::prefix('/v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        // Route::apiResource('/profiles', ProfileController::class)->only(['show']);
        Route::post('/profiles/search', [ProfileController::class, 'search']);
        Route::get('/profiles/{id}', [ProfileController::class, 'show']);
        Route::post('/profiles/{id}', [ProfileController::class, 'update']);

        Route::post('/connections/send', [ConnectionController::class, 'send']);
        Route::post('/connections/{id}/accept', [ConnectionController::class, 'accept']);
        Route::post('/connections/{id}/reject', [ConnectionController::class, 'reject']);
        Route::get('/connections/incoming', [ConnectionController::class, 'incoming']);
        Route::get('/connections/friends', [ConnectionController::class, 'friends']);
        
        Route::apiResource('/posts', PostController::class);
        Route::apiResource('/comments', CommentController::class)->only(['store', 'destroy']);;
        Route::apiResource('/likes', LikeController::class)->only(['store', 'destroy']);;
    });
});