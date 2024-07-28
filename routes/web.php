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
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Rota padrão
Route::get('/', function () {
    return view('site.welcome');
});

Route::get('/saiba-mais', [SiteController::class, 'about'])->name('saiba-mais');

Route::get('login', function () {
    return redirect('/');
})->name('login');

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

        Route::middleware(['role.establishment:superuser'])->group(function () {
  
            // Rotas de estabelecimentos
            Route::get('/estabelecimentos', [EstablishmentController::class, 'index'])->name('admin.establishments.index');
            Route::get('/estabelecimentos/novo', [EstablishmentController::class, 'create'])->name('admin.establishments.create');
            Route::post('/estabelecimentos/novo', [EstablishmentController::class, 'store'])->name('admin.establishments.store');
            Route::get('/estabelecimentos/{establishment}/editar', [EstablishmentController::class, 'edit'])->name('admin.establishments.edit');
            Route::put('/estabelecimentos/{establishment}/editar', [EstablishmentController::class, 'update'])->name('admin.establishments.update');
            Route::get('/estabelecimentos/{establishment}/gerenciar', [EstablishmentController::class, 'manage'])->name('admin.establishments.manage');
            Route::get('/estabelecimentos/{establishment}/detalhes', [EstablishmentController::class, 'view'])->name('admin.establishments.view');
            Route::get('/estabelecimentos/{establishment}/alunos', [EstablishmentController::class, 'students'])->name('admin.establishments.students');
            Route::get('/estabelecimentos/{establishment}/usuarios', [EstablishmentController::class, 'users'])->name('admin.establishments.users');
            Route::get('/estabelecimentos/{establishment}/contratos', [EstablishmentController::class, 'contracts'])->name('admin.establishments.contracts');
            Route::post('/estabelecimentos/{establishment}/contratos/novo', [EstablishmentController::class, 'contractStore'])->name('admin.establishments.contracts.store');
            Route::delete('/estabelecimentos/{establishment}/excluir', [EstablishmentController::class, 'destroy'])->name('admin.establishments.destroy');
            Route::get('/estabelecimentos/{establishment}/restaurar', [EstablishmentController::class, 'restore'])->name('admin.establishments.restore');
        });

        Route::middleware(['role.establishment:admin,superuser'])->group(function () {

            // Rotas de categorias
            Route::get('/categorias', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::get('/categorias/novo', [CategoryController::class, 'create'])->name('admin.categories.create');
            Route::post('/categorias/novo', [CategoryController::class, 'store'])->name('admin.categories.store');
            Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::put('/categorias/{category}/editar', [CategoryController::class, 'update'])->name('admin.categories.update');
            Route::get('/categorias/{category}/detalhes', [CategoryController::class, 'view'])->name('admin.categories.view');
            Route::delete('/categorias/{category}/excluir', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
            Route::get('/categorias/{category}/restaurar', [CategoryController::class, 'restore'])->name('admin.categories.restore');

            // Rotas de alunos
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

            // Rotas de colaboradores
            Route::get('/colaboradores', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/colaboradores/novo', [UserController::class, 'create'])->name('admin.users.create');
            Route::post('/colaboradores/novo', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('/colaboradores/{user}/editar', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/colaboradores/{user}/editar', [UserController::class, 'update'])->name('admin.users.update');
            Route::get('/colaboradores/{user}/detalhes', [UserController::class, 'view'])->name('admin.users.view');
            Route::delete('/colaboradores/{user}/excluir', [UserController::class, 'destroy'])->name('admin.users.destroy');
            Route::get('/colaboradores/{user}/restaurar', [UserController::class, 'restore'])->name('admin.users.restore');
            Route::post('/colaboradores/{user}/vincular', [UserController::class, 'linkEstablishment'])->name('admin.users.establishments.store');
            Route::put('/colaboradores/{user}/vincular/editar', [UserController::class, 'updateLinkEstablishment'])->name('admin.users.establishments.update');
            Route::get('/colaboradores/{user}/desvincular/{establishment}', [UserController::class, 'unlinkEstablishment'])->name('admin.users.establishments.destroy');

            // Rotas de modalidades
            Route::get('/modalidades', [ModalityController::class, 'index'])->name('admin.modalities.index');
            Route::get('/modalidades/novo', [ModalityController::class, 'create'])->name('admin.modalities.create');
            Route::post('/modalidades/novo', [ModalityController::class, 'store'])->name('admin.modalities.store');
            Route::get('/modalidades/{modality}/editar', [ModalityController::class, 'edit'])->name('admin.modalities.edit');
            Route::put('/modalidades/{modality}/editar', [ModalityController::class, 'update'])->name('admin.modalities.update');
            Route::get('/modalidades/{modality}/detalhes', [ModalityController::class, 'view'])->name('admin.modalities.view');
            Route::delete('/modalidades/{modality}/excluir', [ModalityController::class, 'destroy'])->name('admin.modalities.destroy');
            Route::get('/modalidades/{modality}/restaurar', [ModalityController::class, 'restore'])->name('admin.modalities.restore');

            // Rotas de exercicios
            Route::get('/exercicios', [ExerciseController::class, 'index'])->name('admin.exercises.index');
            Route::get('/exercicios/novo', [ExerciseController::class, 'create'])->name('admin.exercises.create');
            Route::post('/exercicios/novo', [ExerciseController::class, 'store'])->name('admin.exercises.store');
            Route::get('/exercicios/{exercises}/editar', [ExerciseController::class, 'edit'])->name('admin.exercises.edit');
            Route::put('/exercicios/{exercises}/editar', [ExerciseController::class, 'update'])->name('admin.exercises.update');
            Route::get('/exercicios/{exercises}/detalhes', [ExerciseController::class, 'view'])->name('admin.exercises.view');
            Route::delete('/exercicios/{exercises}/excluir', [ExerciseController::class, 'destroy'])->name('admin.exercises.destroy');
            Route::get('/exercicios/{exercises}/restaurar', [ExerciseController::class, 'restore'])->name('admin.exercises.restore');
            Route::get('/exercicios/{exercises}/remover-imagem', [ExerciseController::class, 'removeExercisePicture'])->name('admin.exercises.remove-picture');

            // Rotas de agendamento-aulas
            Route::get('/agendamento-aulas', [ClassScheduleController::class, 'index'])->name('admin.class_schedules.index');
            Route::get('/agendamento-aulas/novo', [ClassScheduleController::class, 'create'])->name('admin.class_schedules.create');
            Route::post('/agendamento-aulas/novo', [ClassScheduleController::class, 'store'])->name('admin.class_schedules.store');
            Route::get('/agendamento-aulas/{class_schedules}/editar', [ClassScheduleController::class, 'edit'])->name('admin.class_schedules.edit');
            Route::put('/agendamento-aulas/{class_schedules}/editar', [ClassScheduleController::class, 'update'])->name('admin.class_schedules.update');
            Route::get('/agendamento-aulas/{class_schedules}/detalhes', [ClassScheduleController::class, 'view'])->name('admin.class_schedules.view');
            Route::delete('/agendamento-aulas/{class_schedules}/excluir', [ClassScheduleController::class, 'destroy'])->name('admin.class_schedules.destroy');
            Route::get('/agendamento-aulas/{class_schedules}/restaurar', [ClassScheduleController::class, 'restore'])->name('admin.class_schedules.restore');

            // Rotas de aulas
            Route::get('/aulas', [ClassBookingController::class, 'index'])->name('admin.class_bookings.index');
            Route::get('/aulas/novo', [ClassBookingController::class, 'create'])->name('admin.class_bookings.create');
            Route::post('/aulas/novo', [ClassBookingController::class, 'store'])->name('admin.class_bookings.store');
            Route::get('/aulas/{class_bookings}/editar', [ClassBookingController::class, 'edit'])->name('admin.class_bookings.edit');
            Route::put('/aulas/{class_bookings}/editar', [ClassBookingController::class, 'update'])->name('admin.class_bookings.update');
            Route::get('/aulas/{class_bookings}/detalhes', [ClassBookingController::class, 'view'])->name('admin.class_bookings.view');
            Route::delete('/aulas/{class_bookings}/excluir', [ClassBookingController::class, 'destroy'])->name('admin.class_bookings.destroy');
            Route::get('/aulas/{class_bookings}/restaurar', [ClassBookingController::class, 'restore'])->name('admin.class_bookings.restore');
            Route::get('/get-events', [ClassBookingController::class, 'getEvents'])->name('admin.class_bookings.get_events');

            // Rotas de treinos
            Route::get('/treinos', [WorkoutController::class, 'index'])->name('admin.workouts.index');
            Route::get('/treinos/novo', [WorkoutController::class, 'create'])->name('admin.workouts.create');
            Route::post('/treinos/novo', [WorkoutController::class, 'store'])->name('admin.workouts.store');
            Route::get('/treinos/{workouts}/editar', [WorkoutController::class, 'edit'])->name('admin.workouts.edit');
            Route::put('/treinos/{workouts}/editar', [WorkoutController::class, 'update'])->name('admin.workouts.update');
            Route::get('/treinos/{workouts}/detalhes', [WorkoutController::class, 'view'])->name('admin.workouts.view');
            Route::delete('/treinos/{workouts}/excluir', [WorkoutController::class, 'destroy'])->name('admin.workouts.destroy');
            Route::get('/treinos/{workouts}/restaurar', [WorkoutController::class, 'restore'])->name('admin.workouts.restore');
            
        });
    });
});

// Rotas para app (alunos)
// Route::prefix('app')->group(function () {
//     // Rota de login para alunos
//     Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('student.login');
//     Route::post('/login', [StudentLoginController::class, 'login']);
//     Route::post('/logout', [StudentLoginController::class, 'logout'])->name('student.logout');
    
//     // Rotas protegidas para alunos autenticados
//     Route::middleware(['auth:student', 'verified'])->group(function () {
//         Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('students.dashboard');
        
//         // // Rotas de aulas
//         // Route::get('/aulas', [ClassScheduleController::class, 'index'])->name('admin.class_schedules.index');
//         // Route::get('/aulas/novo', [ClassScheduleController::class, 'create'])->name('admin.class_schedules.create');
//         // Route::post('/aulas/novo', [ClassScheduleController::class, 'store'])->name('admin.class_schedules.store');
//         // Route::get('/aulas/{class_schedules}/editar', [ClassScheduleController::class, 'edit'])->name('admin.class_schedules.edit');
//         // Route::put('/aulas/{class_schedules}/editar', [ClassScheduleController::class, 'update'])->name('admin.class_schedules.update');
//         // Route::get('/aulas/{class_schedules}/detalhes', [ClassScheduleController::class, 'view'])->name('admin.class_schedules.view');
//         // Route::delete('/aulas/{class_schedules}/excluir', [ClassScheduleController::class, 'destroy'])->name('admin.class_schedules.destroy');
//         // Route::get('/aulas/{class_schedules}/restaurar', [ClassScheduleController::class, 'restore'])->name('admin.class_schedules.restore');

//         // // Rotas de reservas
//         // Route::get('/reservas', [ClassBookingController::class, 'index'])->name('admin.class_bookings.index');
//         // Route::get('/reservas/novo', [ClassBookingController::class, 'create'])->name('admin.class_bookings.create');
//         // Route::post('/reservas/novo', [ClassBookingController::class, 'store'])->name('admin.class_bookings.store');
//         // Route::get('/reservas/{class_bookings}/editar', [ClassBookingController::class, 'edit'])->name('admin.class_bookings.edit');
//         // Route::put('/reservas/{class_bookings}/editar', [ClassBookingController::class, 'update'])->name('admin.class_bookings.update');
//         // Route::get('/reservas/{class_bookings}/detalhes', [ClassBookingController::class, 'view'])->name('admin.class_bookings.view');
//         // Route::delete('/reservas/{class_bookings}/excluir', [ClassBookingController::class, 'destroy'])->name('admin.class_bookings.destroy');
//         // Route::get('/reservas/{class_bookings}/restaurar', [ClassBookingController::class, 'restore'])->name('admin.class_bookings.restore');

//         // // Rotas de treinos
//         // Route::get('/treinos', [WorkoutController::class, 'index'])->name('admin.workouts.index');
//         // Route::get('/treinos/novo', [WorkoutController::class, 'create'])->name('admin.workouts.create');
//         // Route::post('/treinos/novo', [WorkoutController::class, 'store'])->name('admin.workouts.store');
//         // Route::get('/treinos/{workouts}/editar', [WorkoutController::class, 'edit'])->name('admin.workouts.edit');
//         // Route::put('/treinos/{workouts}/editar', [WorkoutController::class, 'update'])->name('admin.workouts.update');
//         // Route::get('/treinos/{workouts}/detalhes', [WorkoutController::class, 'view'])->name('admin.workouts.view');
//         // Route::delete('/treinos/{workouts}/excluir', [WorkoutController::class, 'destroy'])->name('admin.workouts.destroy');
//         // Route::get('/treinos/{workouts}/restaurar', [WorkoutController::class, 'restore'])->name('admin.workouts.restore');
//     });
// });

// Rota de seleção de estabelecimento
Route::get('/select-establishment', [EstablishmentController::class, 'selectEstablishment'])->name('select.establishment');
Route::post('/select-establishment', [EstablishmentController::class, 'storeEstablishment'])->name('store.establishment');
