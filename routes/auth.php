<?php

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\MultiAuthController;
use Illuminate\Support\Facades\Route;

// Multi-Authentication Routes
Route::middleware('guest')->group(function () {
    // Multi-Auth Registration
    Route::get('auth/register', [MultiAuthController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('auth/register', [MultiAuthController::class, 'register'])->name('auth.register.post');

    // Multi-Auth Login
    Route::get('auth/login', [MultiAuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('auth/login', [MultiAuthController::class, 'login'])->name('auth.login.post');

    // OTP Routes
    Route::get('auth/verify-otp', [MultiAuthController::class, 'showOtpForm'])->name('auth.verify-otp');
    Route::post('auth/verify-otp', [MultiAuthController::class, 'verifyOtp'])->name('auth.verify-otp.post');
    Route::post('auth/send-otp', [MultiAuthController::class, 'sendOtp'])->name('auth.send-otp');

    // Magic Link Routes
    Route::get('auth/magic-link', [MultiAuthController::class, 'showMagicLinkForm'])->name('auth.magic-link');
    Route::post('auth/magic-link', [MultiAuthController::class, 'sendMagicLink'])->name('auth.send-magic-link');
    Route::get('auth/magic-link/verify/{token}', [MultiAuthController::class, 'verifyMagicLink'])->name('auth.magic-link.verify');

    // Google SSO Routes
    Route::get('auth/google', [MultiAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [MultiAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Multi-Auth Profile Management
    Route::get('auth/profile', [MultiAuthController::class, 'profile'])->name('auth.profile');
    Route::post('auth/add-method', [MultiAuthController::class, 'addAuthMethod'])->name('auth.add-method');
    Route::post('auth/send-otp-for-method', [MultiAuthController::class, 'sendOtpForMethod'])->name('auth.send-otp-for-method');
    Route::post('auth/verify-method/{methodId}', [MultiAuthController::class, 'verifyAuthMethod'])->name('auth.verify-method');
    Route::delete('auth/remove-method/{methodId}', [MultiAuthController::class, 'removeAuthMethod'])->name('auth.remove-method');
    Route::patch('auth/set-primary-method', [MultiAuthController::class, 'setPrimaryAuthMethod'])->name('auth.set-primary-method');
    Route::patch('auth/update-profile', [MultiAuthController::class, 'updateProfile'])->name('auth.update-profile');
    Route::post('auth/logout', [MultiAuthController::class, 'logout'])->name('auth.logout');

    // Original Breeze routes
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.confirm.post');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [MultiAuthController::class, 'logout'])
        ->name('logout');
});
