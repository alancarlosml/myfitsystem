<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'custom.auth' => \App\Http\Middleware\CustomAuthenticate::class,
            'auth.user' => \App\Http\Middleware\CheckUserGuard::class,
            'auth.student' => \App\Http\Middleware\CheckStudentGuard::class,
            'redirectIfAuthenticated' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role.establishment' => \App\Http\Middleware\CheckRoleInEstablishment::class,
            'mobile' => \App\Http\Middleware\CheckMobile::class,
            'student.contract.active' => \App\Http\Middleware\CheckStudentContractStatus::class,
            'user.contract.active' => \App\Http\Middleware\CheckUserContractStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
