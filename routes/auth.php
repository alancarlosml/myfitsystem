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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Rotas para Gestão (Usuários)
Route::prefix('gestao')->group(function () {
    // Rotas de autenticação
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('user.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('user.logout');

    // Rotas protegidas para usuários autenticados
    Route::middleware(['auth:user', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

        // Rotas de perfil e segurança
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
        Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('user.profile.destroy');

        // Rota de verificação de e-mail
        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['throttle:6,1'])
            ->name('user.verification.send');

        // Rota de confirmação de senha
        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('user.password.confirm');
        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

        // Rota de atualização de senha
        Route::put('/password/update', [PasswordController::class, 'update'])->name('user.password.update');
    });
});

// Rotas para Aplicativo (Alunos)
Route::prefix('app')->group(function () {
    // Rotas de autenticação
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('student.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('student.logout');

    // Rotas protegidas para alunos autenticados
    Route::middleware(['auth:student', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');

        // Rota de seleção de estabelecimento
        Route::get('/select-establishment', [EstablishmentController::class, 'selectEstablishment'])->name('select.establishment');
        Route::post('/store-establishment', [EstablishmentController::class, 'storeEstablishment'])->name('store.establishment');

        // Outras rotas protegidas para alunos aqui
    });

    // Rotas para registro, recuperação de senha e verificação de e-mail
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('student.register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('student.password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('student.password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('student.password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('student.password.store');

    // Rota de verificação de e-mail para alunos
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('student.verification.notice');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('student.verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('student.verification.send');

    // Rota de confirmação de senha para alunos
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('student.password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Rota de atualização de senha para alunos
    Route::put('/password/update', [PasswordController::class, 'update'])->name('student.password.update');
});

return Route::get('/', function () {return view('welcome');});
