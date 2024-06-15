<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $routes = [
            'user' => '/gestao/dashboard',
            'student' => '/app/dashboard',
        ];

        foreach ($routes as $key => $route) {
            if (Auth::guard($key)->check()) {
                return redirect($route);
            }
        }

        return $next($request);
    }
}
