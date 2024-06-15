<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthenticate
{
    public function handle(Request $request, Closure $next, $guard = 'user')
    {
        if (Auth::guard($guard)->check()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Redirect based on request path (optional, adjust as needed)
        if ($request->is('gestao/*')) {
            return redirect()->route('user.login');
        } elseif ($request->is('app/*')) {
            return redirect()->route('student.login');
        }

        return redirect()->route('login'); // Default route
    }
}
