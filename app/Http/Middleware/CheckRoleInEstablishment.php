<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AccessControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleInEstablishment
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/gestao/login')->with('error', 'Você precisa estar autenticado para acessar esta área.');
        }

        $establishmentId = session('establishment_id');
        
        $accessControlService = app(AccessControlService::class);

        // Superuser check - can access routes without establishment_id
        if (in_array('superuser', $roles)) {
            // Check if user has superuser role (via role_user pivot table)
            $superuserRole = \App\Models\Role::where('name', 'superuser')->first();
            if ($superuserRole) {
                $hasSuperuserRole = \DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('role_id', $superuserRole->id)
                    ->exists();
                    
                if ($hasSuperuserRole) {
                    return $next($request);
                }
            }
        }

        // For non-superuser roles, check role in current establishment
        if ($establishmentId) {
            $establishment = \App\Models\Establishment::find($establishmentId);

            if (!$establishment || !$accessControlService->establishmentHasActiveSystemContract($establishment)) {
                return redirect('/')->with('error', 'Contrato do estabelecimento expirado. Entre em contato com o administrador.');
            }

            $userRole = $user->getRoleForEstablishment($establishmentId);

            if ($userRole && in_array($userRole->name, $roles)) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
