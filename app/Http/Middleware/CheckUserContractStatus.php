<?php

namespace App\Http\Middleware;

use App\Services\AccessControlService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserContractStatus
{
    protected AccessControlService $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    /**
     * Handle an incoming request.
     * Verifica se o usuário ainda pode acessar o estabelecimento (contrato ativo).
     * Superusers são isentos desta verificação.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('user')->check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::guard('user')->user();

        // Superuser pode acessar qualquer coisa
        if ($this->accessControlService->userIsSuperuser($user)) {
            return $next($request);
        }

        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            // Se não tem establishment selecionado, redireciona para seleção
            return redirect()->route('select.establishment');
        }

        $establishment = \App\Models\Establishment::find($establishmentId);
        
        if (!$establishment) {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')
                ->with('error', 'Estabelecimento não encontrado.');
        }

        // Verifica se o estabelecimento tem contrato ativo com a plataforma
        if (!$this->accessControlService->establishmentHasActiveSystemContract($establishment)) {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')
                ->with('error', 'O contrato do estabelecimento está vencido. Entre em contato com o administrador do sistema.');
        }

        return $next($request);
    }
}
