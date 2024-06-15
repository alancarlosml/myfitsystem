<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $guard = 'user';
        $user = Auth::guard($guard)->user();

        if (!$user || !$user->roles->pluck('name')->intersect($roles)->count()) {
            return redirect('/gestao/login'); // ou use abort(403);
        }

        return $next($request);
    }
}
