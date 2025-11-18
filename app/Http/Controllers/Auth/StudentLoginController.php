<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AccessControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    protected AccessControlService $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();
            $establishments = $this->accessControlService->getAccessibleEstablishmentsForStudent($student);
            
            if ($establishments->count() == 1) {
                $establishment = $establishments->first();
                session(['establishment_id' => $establishment->id, 'guard' => 'student']);
                $request->session()->regenerate();
                return redirect()->intended('/app/dashboard');
            } elseif ($establishments->count() > 1) {
                session(['guard' => 'student']);
                $request->session()->regenerate();
                return redirect()->route('select.establishment');
            } else {
                Auth::guard('student')->logout();
                return redirect()->route('student.login')->with('error', 'Nenhum estabelecimento disponível. Regularize os pagamentos do seu plano.');
            }
        }

        return redirect()->route('student.login')->with('error', 'Credenciais inválidas.');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}
