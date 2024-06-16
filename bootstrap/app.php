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
            'startSession' => \Illuminate\Session\Middleware\StartSession::class,
            'custom.auth' => \App\Http\Middleware\CustomAuthenticate::class,
            'auth.user' => \App\Http\Middleware\CheckUserGuard::class,
            'auth.student' => \App\Http\Middleware\CheckStudentGuard::class,
            'redirectIfAuthenticated' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role.establishment' => \App\Http\Middleware\CheckRoleInEstablishment::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
