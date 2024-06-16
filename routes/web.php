<?php

use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ModalityController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\ClassBookingController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

// Rota padrão
Route::get('/', function () {
    return view('welcome');
});

// Rotas para configuração do perfil do usuário autenticado
Route::middleware(['auth:user', 'verified'])->group(function () {
    Route::get('/configuracoes', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/configuracoes', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/configuracoes', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inclusão de rotas de autenticação padrão do Laravel
require __DIR__.'/auth.php';

// Rotas para gestão (usuários)
Route::prefix('gestao')->group(function () {
    // Rota de login para usuários
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('user.logout');
    
    // Rotas protegidas para usuários autenticados
    Route::middleware(['auth:user', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('users.dashboard');
        
        // Rotas de usuários
        Route::resource('usuarios', UserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'show' => 'admin.users.view',
            'destroy' => 'admin.users.destroy',
        ])->except(['show']);

        Route::get('usuarios/{user}/detalhes', [UserController::class, 'view'])->name('admin.users.view');
        Route::get('usuarios/{user}/restaurar', [UserController::class, 'restore'])->name('admin.users.restore');
        Route::post('usuarios/{user}/vincular', [UserController::class, 'linkEstablishment'])->name('admin.users.establishments.store');
        Route::put('usuarios/{user}/vincular/editar', [UserController::class, 'updateLinkEstablishment'])->name('admin.users.establishments.update');
        Route::get('usuarios/{user}/desvincular/{establishment}', [UserController::class, 'unlinkEstablishment'])->name('admin.users.establishments.destroy');
        
        // Rotas de estabelecimentos
        Route::resource('estabelecimentos', EstablishmentController::class)->names([
            'index' => 'admin.establishments.index',
            'create' => 'admin.establishments.create',
            'store' => 'admin.establishments.store',
            'edit' => 'admin.establishments.edit',
            'update' => 'admin.establishments.update',
            'show' => 'admin.establishments.view',
            'destroy' => 'admin.establishments.destroy',
        ])->except(['show']);
        
        Route::get('estabelecimentos/{establishment}/detalhes', [EstablishmentController::class, 'view'])->name('admin.establishments.view');
        Route::get('estabelecimentos/{establishment}/gerenciar', [EstablishmentController::class, 'manage'])->name('admin.establishments.manage');
        Route::get('estabelecimentos/{establishment}/alunos', [EstablishmentController::class, 'students'])->name('admin.establishments.students');
        Route::get('estabelecimentos/{establishment}/usuarios', [EstablishmentController::class, 'users'])->name('admin.establishments.users');
        Route::get('estabelecimentos/{establishment}/contratos', [EstablishmentController::class, 'contracts'])->name('admin.establishments.contracts');
        Route::post('estabelecimentos/{establishment}/contratos/novo', [EstablishmentController::class, 'contractStore'])->name('admin.establishments.contracts.store');
        Route::get('estabelecimentos/{establishment}/restaurar', [EstablishmentController::class, 'restore'])->name('admin.establishments.restore');
        
        // Outras rotas (exemplo com categorias)
        Route::resource('categorias', CategoryController::class)->names([
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'show' => 'admin.categories.view',
            'destroy' => 'admin.categories.destroy',
        ])->except(['show']);
        Route::get('categorias/{category}/detalhes', [CategoryController::class, 'view'])->name('admin.categories.view');
        Route::get('categorias/{category}/restaurar', [CategoryController::class, 'restore'])->name('admin.categories.restore');

        Route::get('/alunos', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/alunos/novo', [StudentController::class, 'create'])->name('admin.students.create');
        Route::post('/alunos/novo', [StudentController::class, 'store'])->name('admin.students.store');
        Route::get('/alunos/{student}/editar', [StudentController::class, 'edit'])->name('admin.students.edit');
        Route::put('/alunos/{student}/editar', [StudentController::class, 'update'])->name('admin.students.update');
        Route::get('/alunos/{student}/detalhes', [StudentController::class, 'view'])->name('admin.students.view');
        Route::delete('/alunos/{student}/excluir', [StudentController::class, 'destroy'])->name('admin.students.destroy');
        Route::get('/alunos/{student}/restaurar', [StudentController::class, 'restore'])->name('admin.students.restore');
        Route::get('/alunos/{student}/contratos/{establishment}', [StudentController::class, 'contracts'])->name('admin.students.contracts');
        Route::post('/alunos/{student}/contratos/{establishment}/novo', [StudentController::class, 'contractStore'])->name('admin.students.contracts.store');
    });
});

// Rotas para app (alunos)
Route::prefix('app')->group(function () {
    // Rota de login para alunos
    Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login', [StudentLoginController::class, 'login']);
    Route::post('/logout', [StudentLoginController::class, 'logout'])->name('student.logout');
    
    // Rotas protegidas para alunos autenticados
    Route::middleware(['auth:student', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('students.dashboard');
        // Outras rotas protegidas para alunos aqui
    });
});

// Rota de seleção de estabelecimento
Route::get('/select-establishment', [EstablishmentController::class, 'selectEstablishment'])->name('select.establishment');
Route::post('/select-establishment', [EstablishmentController::class, 'storeEstablishment'])->name('store.establishment');
