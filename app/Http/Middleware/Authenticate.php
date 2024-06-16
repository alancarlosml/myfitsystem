<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('gestao') || $request->is('gestao/*')) {
                return route('gestao.login');
            } elseif ($request->is('app') || $request->is('app/*')) {
                return route('app.login');
            }
            return route('login'); // Default route if neither condition is met
        }
    }
}