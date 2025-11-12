<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    // Public routes (tidak memerlukan autentikasi)
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('auth.verify-email');
    Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('auth.resend-verification-email');

    // Protected routes (memerlukan autentikasi)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/me', [AuthController::class, 'currentUser'])->name('auth.current-user');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('auth.update-profile');
    });
});

// Contoh protected route dengan email verification
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Route-route yang memerlukan email terverifikasi
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Contoh protected route untuk seller
Route::middleware(['auth:sanctum', 'verified', 'seller'])->group(function () {
    // Route-route khusus penjual
});

// Contoh protected route untuk buyer
Route::middleware(['auth:sanctum', 'verified', 'buyer'])->group(function () {
    // Route-route khusus pembeli
});
