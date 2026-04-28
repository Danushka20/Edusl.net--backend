<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('health', [HealthController::class, 'index']);
    Route::get('health/db', [HealthController::class, 'db']);

    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/google', [AuthController::class, 'googleLogin']);

    Route::middleware([JwtMiddleware::class])->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::get('user/profile', [UserController::class, 'profile']);
        Route::get('user/dashboard', [UserController::class, 'dashboard']);

        Route::middleware(['role:admin'])->group(function () {
            Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
            Route::get('admin/users', [AdminController::class, 'users']);
        });
    });
});
