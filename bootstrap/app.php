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
            'checkRole' => \App\Http\Middleware\CheckRole::class,
            'checkUserGuard' => \App\Http\Middleware\CheckUserGuard::class,
            'checkStudentGuard' => \App\Http\Middleware\CheckStudentGuard::class,
            'redirectIfAuthenticated' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
