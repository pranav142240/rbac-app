<?php

use App\Http\Controllers\Auth\MultiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public authentication routes (if needed for mobile apps in future)
Route::prefix('auth')->group(function () {
    Route::post('register', [MultiAuthController::class, 'register']);
    Route::post('login', [MultiAuthController::class, 'login']);
    Route::post('send-otp', [MultiAuthController::class, 'sendOtp'])->middleware('otp.rate.limit');
    Route::post('verify-otp', [MultiAuthController::class, 'verifyOtp']);
});

// Protected routes for authenticated users (if needed for mobile apps in future)
Route::middleware('auth:web')->group(function () {
    // User profile and auth methods
    Route::prefix('auth')->group(function () {
        Route::get('profile', [MultiAuthController::class, 'profile']);
        Route::post('add-method', [MultiAuthController::class, 'addAuthMethod']);
        Route::post('logout', [MultiAuthController::class, 'logout']);
    });

    // Additional API endpoints can be added here in the future
    // For now, all RBAC functionality is handled via web routes
});
