<?php

namespace App\Http\Middleware;

use App\Services\AccessControlService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStudentContractStatus
{
    protected AccessControlService $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    /**
     * Handle an incoming request.
     * Verifica se o aluno ainda tem contrato válido e pago para acessar o sistema.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            // Se não tem establishment selecionado, redireciona para seleção
            return redirect()->route('select.establishment');
        }

        $establishment = \App\Models\Establishment::find($establishmentId);
        
        if (!$establishment) {
            Auth::guard('student')->logout();
            return redirect()->route('student.login')
                ->with('error', 'Estabelecimento não encontrado.');
        }

        // Verifica se o estabelecimento tem contrato ativo com a plataforma
        if (!$this->accessControlService->establishmentHasActiveSystemContract($establishment)) {
            Auth::guard('student')->logout();
            return redirect()->route('student.login')
                ->with('error', 'O estabelecimento está com o contrato vencido. Entre em contato com a administração.');
        }

        // Verifica se o aluno tem contrato válido
        if (!$this->accessControlService->studentHasValidAccessContract($student, $establishment)) {
            Auth::guard('student')->logout();
            return redirect()->route('student.login')
                ->with('error', 'Seu plano está vencido ou pendente de pagamento. Regularize sua situação para continuar acessando.');
        }

        return $next($request);
    }
}
