<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:user', 'verified'])->group(function () {
    Route::get('/configuracoes', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/configuracoes', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/configuracoes', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('gestao')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\UserLoginController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [\App\Http\Controllers\Auth\UserLoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Auth\UserLoginController::class, 'logout'])->name('user.logout');
});

Route::prefix('app')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\StudentLoginController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login', [\App\Http\Controllers\Auth\StudentLoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Auth\StudentLoginController::class, 'logout'])->name('student.logout');
});

Route::prefix('gestao')->middleware(['auth:user', 'verified', 'checkRole:superuser'])->group(function () {

    Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/usuarios/novo', [\App\Http\Controllers\UserController::class, 'create'])->name('admin.users.create');
    Route::post('/usuarios/novo', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
    Route::get('/usuarios/{user}/editar', [\App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/usuarios/{user}/editar', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
    Route::get('/usuarios/{user}/detalhes', [\App\Http\Controllers\UserController::class, 'view'])->name('admin.users.view');
    Route::delete('/usuarios/{user}/excluir', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/usuarios/{user}/restaurar', [\App\Http\Controllers\UserController::class, 'restore'])->name('admin.users.restore');
    Route::post('/usuarios/{user}/vincular', [\App\Http\Controllers\UserController::class, 'linkEstablishment'])->name('admin.users.establishments.store');
    Route::put('/usuarios/{user}/vincular/editar', [\App\Http\Controllers\UserController::class, 'updateLinkEstablishment'])->name('admin.users.establishments.update');
    Route::get('/usuarios/{user}/desvincular/{establishment}', [\App\Http\Controllers\UserController::class, 'unlinkEstablishment'])->name('admin.users.establishments.destroy');

    Route::get('/estabelecimentos', [\App\Http\Controllers\EstablishmentController::class, 'index'])->name('admin.establishments.index');
    Route::get('/estabelecimentos/novo', [\App\Http\Controllers\EstablishmentController::class, 'create'])->name('admin.establishments.create');
    Route::post('/estabelecimentos/novo', [\App\Http\Controllers\EstablishmentController::class, 'store'])->name('admin.establishments.store');
    Route::get('/estabelecimentos/{establishment}/editar', [\App\Http\Controllers\EstablishmentController::class, 'edit'])->name('admin.establishments.edit');
    Route::put('/estabelecimentos/{establishment}/editar', [\App\Http\Controllers\EstablishmentController::class, 'update'])->name('admin.establishments.update');
    Route::get('/estabelecimentos/{establishment}/gerenciar', [\App\Http\Controllers\EstablishmentController::class, 'manage'])->name('admin.establishments.manage');
    Route::get('/estabelecimentos/{establishment}/detalhes', [\App\Http\Controllers\EstablishmentController::class, 'view'])->name('admin.establishments.view');
    Route::get('/estabelecimentos/{establishment}/alunos', [\App\Http\Controllers\EstablishmentController::class, 'students'])->name('admin.establishments.students');
    Route::get('/estabelecimentos/{establishment}/usuarios', [\App\Http\Controllers\EstablishmentController::class, 'users'])->name('admin.establishments.users');
    Route::get('/estabelecimentos/{establishment}/contratos', [\App\Http\Controllers\EstablishmentController::class, 'contracts'])->name('admin.establishments.contracts');
    Route::post('/estabelecimentos/{establishment}/contratos/novo', [\App\Http\Controllers\EstablishmentController::class, 'contractStore'])->name('admin.establishments.contracts.store');
    Route::delete('/estabelecimentos/{establishment}/excluir', [\App\Http\Controllers\EstablishmentController::class, 'destroy'])->name('admin.establishments.destroy');
    Route::get('/estabelecimentos/{establishment}/restaurar', [\App\Http\Controllers\EstablishmentController::class, 'restore'])->name('admin.establishments.restore');
});


Route::prefix('gestao')->middleware(['auth:user', 'checkRole:superuser,admin', 'verified'])->group(function () {
    
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'userDashboard'])->name('users.dashboard');
    
    // Route::get('/instrutores', [\App\Http\Controllers\InstructorController::class, 'index'])->name('admin.instructors.index');
    // Route::get('/instrutores/novo', [\App\Http\Controllers\InstructorController::class, 'create'])->name('admin.instructors.create');
    // Route::post('/instrutores/novo', [\App\Http\Controllers\InstructorController::class, 'store'])->name('admin.instructors.store');
    // Route::get('/instrutores/{instructor}/editar', [\App\Http\Controllers\InstructorController::class, 'edit'])->name('admin.instructors.edit');
    // Route::put('/instrutores/{instructor}/editar', [\App\Http\Controllers\InstructorController::class, 'update'])->name('admin.instructors.update');
    // Route::get('/instrutores/{instructor}/detalhes', [\App\Http\Controllers\InstructorController::class, 'view'])->name('admin.instructors.view');
    // Route::delete('/instrutores/{instructor}/excluir', [\App\Http\Controllers\InstructorController::class, 'destroy'])->name('admin.instructors.destroy');
    // Route::get('/instrutores/{instructor}/restaurar', [\App\Http\Controllers\InstructorController::class, 'restore'])->name('admin.instructors.restore');

    Route::get('/alunos', [\App\Http\Controllers\StudentController::class, 'index'])->name('admin.students.index');
    Route::get('/alunos/novo', [\App\Http\Controllers\StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/alunos/novo', [\App\Http\Controllers\StudentController::class, 'store'])->name('admin.students.store');
    Route::get('/alunos/{student}/editar', [\App\Http\Controllers\StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/alunos/{student}/editar', [\App\Http\Controllers\StudentController::class, 'update'])->name('admin.students.update');
    Route::get('/alunos/{student}/detalhes', [\App\Http\Controllers\StudentController::class, 'view'])->name('admin.students.view');
    Route::delete('/alunos/{student}/excluir', [\App\Http\Controllers\StudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::get('/alunos/{student}/restaurar', [\App\Http\Controllers\StudentController::class, 'restore'])->name('admin.students.restore');
    Route::get('/alunos/{student}/contratos/{establishment}', [\App\Http\Controllers\StudentController::class, 'contracts'])->name('admin.students.contracts');
    Route::post('/alunos/{student}/contratos/{establishment}/novo', [\App\Http\Controllers\StudentController::class, 'contractStore'])->name('admin.students.contracts.store');

    Route::get('/categorias', [\App\Http\Controllers\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categorias/novo', [\App\Http\Controllers\CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categorias/novo', [\App\Http\Controllers\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categorias/{category}/editar', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categorias/{category}/editar', [\App\Http\Controllers\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::get('/categorias/{category}/detalhes', [\App\Http\Controllers\CategoryController::class, 'view'])->name('admin.categories.view');
    Route::delete('/categorias/{category}/excluir', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/categorias/{category}/restaurar', [\App\Http\Controllers\CategoryController::class, 'restore'])->name('admin.categories.restore');

    Route::get('/modalidades', [\App\Http\Controllers\ModalityController::class, 'index'])->name('admin.modalities.index');
    Route::get('/modalidades/novo', [\App\Http\Controllers\ModalityController::class, 'create'])->name('admin.modalities.create');
    Route::post('/modalidades/novo', [\App\Http\Controllers\ModalityController::class, 'store'])->name('admin.modalities.store');
    Route::get('/modalidades/{modality}/editar', [\App\Http\Controllers\ModalityController::class, 'edit'])->name('admin.modalities.edit');
    Route::put('/modalidades/{modality}/editar', [\App\Http\Controllers\ModalityController::class, 'update'])->name('admin.modalities.update');
    Route::get('/modalidades/{modality}/detalhes', [\App\Http\Controllers\ModalityController::class, 'view'])->name('admin.modalities.view');
    Route::delete('/modalidades/{modality}/excluir', [\App\Http\Controllers\ModalityController::class, 'destroy'])->name('admin.modalities.destroy');
    Route::get('/modalidades/{modality}/restaurar', [\App\Http\Controllers\ModalityController::class, 'restore'])->name('admin.modalities.restore');

    Route::get('/aulas', [\App\Http\Controllers\ClassScheduleController::class, 'index'])->name('admin.class_schedules.index');
    Route::get('/aulas/novo', [\App\Http\Controllers\ClassScheduleController::class, 'create'])->name('admin.class_schedules.create');
    Route::post('/aulas/novo', [\App\Http\Controllers\ClassScheduleController::class, 'store'])->name('admin.class_schedules.store');
    Route::get('/aulas/{class_schedules}/editar', [\App\Http\Controllers\ClassScheduleController::class, 'edit'])->name('admin.class_schedules.edit');
    Route::put('/aulas/{class_schedules}/editar', [\App\Http\Controllers\ClassScheduleController::class, 'update'])->name('admin.class_schedules.update');
    Route::get('/aulas/{class_schedules}/detalhes', [\App\Http\Controllers\ClassScheduleController::class, 'view'])->name('admin.class_schedules.view');
    Route::delete('/aulas/{class_schedules}/excluir', [\App\Http\Controllers\ClassScheduleController::class, 'destroy'])->name('admin.class_schedules.destroy');
    Route::get('/aulas/{class_schedules}/restaurar', [\App\Http\Controllers\ClassScheduleController::class, 'restore'])->name('admin.class_schedules.restore');

    Route::get('/reservas', [\App\Http\Controllers\ClassBookingController::class, 'index'])->name('admin.class_bookings.index');
    Route::get('/reservas/novo', [\App\Http\Controllers\ClassBookingController::class, 'create'])->name('admin.class_bookings.create');
    Route::post('/reservas/novo', [\App\Http\Controllers\ClassBookingController::class, 'store'])->name('admin.class_bookings.store');
    Route::get('/reservas/{class_bookings}/editar', [\App\Http\Controllers\ClassBookingController::class, 'edit'])->name('admin.class_bookings.edit');
    Route::put('/reservas/{class_bookings}/editar', [\App\Http\Controllers\ClassBookingController::class, 'update'])->name('admin.class_bookings.update');
    Route::get('/reservas/{class_bookings}/detalhes', [\App\Http\Controllers\ClassBookingController::class, 'view'])->name('admin.class_bookings.view');
    Route::delete('/reservas/{class_bookings}/excluir', [\App\Http\Controllers\ClassBookingController::class, 'destroy'])->name('admin.class_bookings.destroy');
    Route::get('/reservas/{class_bookings}/restaurar', [\App\Http\Controllers\ClassBookingController::class, 'restore'])->name('admin.class_bookings.restore');

    Route::get('/exercicios', [\App\Http\Controllers\ExerciseController::class, 'index'])->name('admin.exercises.index');
    Route::get('/exercicios/novo', [\App\Http\Controllers\ExerciseController::class, 'create'])->name('admin.exercises.create');
    Route::post('/exercicios/novo', [\App\Http\Controllers\ExerciseController::class, 'store'])->name('admin.exercises.store');
    Route::get('/exercicios/{exercises}/editar', [\App\Http\Controllers\ExerciseController::class, 'edit'])->name('admin.exercises.edit');
    Route::put('/exercicios/{exercises}/editar', [\App\Http\Controllers\ExerciseController::class, 'update'])->name('admin.exercises.update');
    Route::get('/exercicios/{exercises}/detalhes', [\App\Http\Controllers\ExerciseController::class, 'view'])->name('admin.exercises.view');
    Route::delete('/exercicios/{exercises}/excluir', [\App\Http\Controllers\ExerciseController::class, 'destroy'])->name('admin.exercises.destroy');
    Route::get('/exercicios/{exercises}/restaurar', [\App\Http\Controllers\ExerciseController::class, 'restore'])->name('admin.exercises.restore');

    Route::get('/treinos', [\App\Http\Controllers\WorkoutController::class, 'index'])->name('admin.workouts.index');
    Route::get('/treinos/novo', [\App\Http\Controllers\WorkoutController::class, 'create'])->name('admin.workouts.create');
    Route::post('/treinos/novo', [\App\Http\Controllers\WorkoutController::class, 'store'])->name('admin.workouts.store');
    Route::get('/treinos/{workouts}/editar', [\App\Http\Controllers\WorkoutController::class, 'edit'])->name('admin.workouts.edit');
    Route::put('/treinos/{workouts}/editar', [\App\Http\Controllers\WorkoutController::class, 'update'])->name('admin.workouts.update');
    Route::get('/treinos/{workouts}/detalhes', [\App\Http\Controllers\WorkoutController::class, 'view'])->name('admin.workouts.view');
    Route::delete('/treinos/{workouts}/excluir', [\App\Http\Controllers\WorkoutController::class, 'destroy'])->name('admin.workouts.destroy');
    Route::get('/treinos/{workouts}/restaurar', [\App\Http\Controllers\WorkoutController::class, 'restore'])->name('admin.workouts.restore');
});


Route::prefix('app')->middleware(['auth:student', 'verified'])->group(function () {
    
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'studentDashboard'])->name('students.dashboard');
    
});


