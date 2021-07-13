<?php

use App\Http\Controllers\Console\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Console\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Console\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Console\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Console\Auth\NewPasswordController;
use App\Http\Controllers\Console\Auth\PasswordResetLinkController;
use App\Http\Controllers\Console\Auth\RegisteredUserController;
use App\Http\Controllers\Console\Auth\VerifyEmailController;
use App\Http\Controllers\Console\IndexController;
use Illuminate\Support\Facades\Route;

// コンソール認証
Route::namespace('Console')->domain(env('APP_CONSOLE_URL'))->group(function () {
    Route::group(['middleware' => 'view.console'], function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->middleware('guest')
            ->name('console.register');

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware('guest');

        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware('guest')
            ->name('console.login');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware('guest');

        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->middleware('guest')
            ->name('console.password.request');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware('guest')
            ->name('console.password.email');

        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->middleware('guest')
            ->name('console.password.reset');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware('guest')
            ->name('console.password.update');

        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->middleware('auth:console')
            ->name('console.verification.notice');

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth:console', 'signed', 'throttle:6,1'])
            ->name('console.verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth:console', 'throttle:6,1'])
            ->name('console.verification.send');

        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware('auth:console')
            ->name('console.password.confirm');

        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
            ->middleware('auth:console');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth:console')
            ->name('console.logout');

        // 
        Route::get('/', function () {
            return view('welcome');
        })->middleware(['guest']);

        Route::get('/dashboard', [IndexController::class, 'dashboard'])->name('console.dashboard');
    });
});
