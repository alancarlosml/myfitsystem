<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Rotas para Gestão
Route::prefix('gestao')->middleware('guest:user')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('user.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('user.password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('user.password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('user.password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('user.password.store');
});

Route::prefix('gestao')->middleware(['custom.auth:user'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('user.verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('user.password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('user.password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('user.logout');
});

// Rotas para Aplicativo
Route::prefix('app')->middleware('guest:student')->group(function () {
    Route::get('cadastro', [RegisteredUserController::class, 'create'])->name('student.register');
    Route::post('cadastro', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('student.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('student.password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('student.password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('student.password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('student.password.store');
});

Route::prefix('app')->middleware(['custom.auth:student'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('student.verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('student.verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('student.verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('student.password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('student.password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('student.logout');
});
