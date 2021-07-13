<?php

use App\Http\Controllers\Manager\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Manager\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Manager\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Manager\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Manager\Auth\NewPasswordController;
use App\Http\Controllers\Manager\Auth\PasswordResetLinkController;
use App\Http\Controllers\Manager\Auth\RegisteredUserController;
use App\Http\Controllers\Manager\Auth\VerifyEmailController;
use App\Http\Controllers\Manager\IndexController;
use Illuminate\Support\Facades\Route;

// コンソール認証
Route::namespace('Manager')->domain(env('APP_MANAGER_URL'))->group(function () {
    Route::group(['middleware' => 'view.manager'], function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->middleware('guest')
            ->name('manager.register');

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware('guest');

        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware('guest')
            ->name('manager.login');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware('guest');

        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->middleware('guest')
            ->name('manager.password.request');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware('guest')
            ->name('manager.password.email');

        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->middleware('guest')
            ->name('manager.password.reset');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware('guest')
            ->name('manager.password.update');

        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->middleware('auth:manager')
            ->name('manager.verification.notice');

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth:manager', 'signed', 'throttle:6,1'])
            ->name('manager.verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth:manager', 'throttle:6,1'])
            ->name('manager.verification.send');

        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware('auth:manager')
            ->name('manager.password.confirm');

        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
            ->middleware('auth:manager');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth:manager')
            ->name('manager.logout');

        //
        Route::get('/', function () {
            return view('welcome');
        })->middleware(['guest']);

        Route::get('/dashboard', [IndexController::class, 'dashboard'])->name('manager.dashboard');
    });
});
