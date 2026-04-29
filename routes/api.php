<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([CorsMiddleware::class])->group(function () {
    Route::options('{any}', function () {
        return response()->json([], 200);
    })->where('any', '.*');

    Route::get('health', [HealthController::class, 'index']);
    Route::get('health/db', [HealthController::class, 'db']);

    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/google', [AuthController::class, 'googleLogin']);
    Route::get('certificates/{share_token}/share', [CertificateController::class, 'share']);

    Route::middleware([JwtMiddleware::class])->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::get('user', [AuthController::class, 'me']);
        Route::get('user/profile', [UserController::class, 'profile']);
        Route::get('user/dashboard', [UserController::class, 'dashboard']);

        Route::get('batches', [BatchController::class, 'index']);
        Route::get('batches/{batch}', [BatchController::class, 'show']);
        Route::get('batches/{batch}/students', [BatchController::class, 'students']);

        Route::get('students/{student}/files', [StudentFileController::class, 'index']);
        Route::get('students/{student}/certificates', [CertificateController::class, 'index']);

        Route::middleware(['role:admin'])->group(function () {
            Route::post('batches', [BatchController::class, 'store']);
            Route::put('batches/{batch}', [BatchController::class, 'update']);
            Route::delete('batches/{batch}', [BatchController::class, 'destroy']);

            Route::post('batches/{batch}/students', [StudentController::class, 'storeForBatch']);
            Route::put('students/{student}', [StudentController::class, 'update']);
            Route::delete('students/{student}', [StudentController::class, 'destroy']);

            Route::post('students/{student}/files', [StudentFileController::class, 'store']);
            Route::post('students/{student}/certificates', [CertificateController::class, 'store']);

            Route::get('users', [AdminController::class, 'users']);
            Route::put('users/{user}/role', [AdminController::class, 'updateRole']);
        });
    });
});
