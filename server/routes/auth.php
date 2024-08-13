<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\TwoFactorAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

//.............................Two factor authentication....................................................

Route::post('/2fa-pre-login', [TwoFactorAuthController::class, 'prelogin'])
    ->middleware('guest')
    ->name('2fa-pre-login');

Route::post('/2fa-register', [TwoFactorAuthController::class, 'register'])
    ->middleware('guest')
    ->name('2fa-register');

// with 2fa token
Route::post('/2fa-challenge', [TwoFactorAuthController::class, 'challenge'])
    ->middleware('guest')
    ->name('2fa-challenge');
//.....................

Route::post('/2fa-login', [TwoFactorAuthController::class, 'login'])
    ->middleware('guest')
    ->name('2fa-login');

// with Username and password
Route::post('/2fa-qrcode', [TwoFactorAuthController::class, 'getQrCode'])
    ->middleware('guest')
    ->name('2fa-qrcode');
//.....................

// with 2fa token
Route::post('/2fa-code', [TwoFactorAuthController::class, 'get2faQrCode'])
    ->middleware('guest')
    ->name('2fa-code');
//.....................

Route::put('/2fa-enable', [TwoFactorAuthController::class, 'enableTwoFactorAuthentication'])
    ->middleware('guest')
    ->name('enable-2fa');

Route::put('/2fa-disable', [TwoFactorAuthController::class, 'disableTwoFactorAuthentication'])
    ->middleware('guest')
    ->name('disable-2fa');

//..........................................................................................................

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
