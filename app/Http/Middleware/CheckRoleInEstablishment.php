<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleInEstablishment
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        #$establishmentId = session('establishment_id');
        $establishmentId = 3;

        if ($user && $establishmentId) {
            $userRole = $user->getRoleForEstablishment($establishmentId);

            if ($userRole && in_array($userRole->name, $roles)) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
