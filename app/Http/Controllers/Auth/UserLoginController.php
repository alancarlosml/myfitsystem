<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AccessControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLoginController extends Controller
{
    protected AccessControlService $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('user')->user();

            // Verifica se o usuário é superuser
            $superuserRole = \App\Models\Role::where('name', 'superuser')->first();
            $isSuperuser = false;
            
            if ($superuserRole) {
                $isSuperuser = \DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('role_id', $superuserRole->id)
                    ->exists();
            }

            // Superuser pode acessar sem selecionar establishment
            if ($isSuperuser) {
                session(['guard' => 'user']);
                // Superuser não precisa de establishment_id, mas pode ter vários estabelecimentos
                // Se tiver apenas um, definimos automaticamente para facilitar
                $establishments = $user->establishments()->get();
                if ($establishments->count() == 1) {
                    session(['establishment_id' => $establishments->first()->id]);
                } else {
                    // Não define establishment_id, permitindo acesso a todos
                    session()->forget('establishment_id');
                }
                $request->session()->regenerate();
                return redirect('/gestao/dashboard');
            }

            // Para usuários normais, verifica estabelecimentos vinculados
            $establishments = $this->accessControlService->getAccessibleEstablishmentsForUser($user);
            
            if ($establishments->count() == 1) {
                $establishment = $establishments->first();
                session(['establishment_id' => $establishment->id, 'guard' => 'user']);
                $request->session()->regenerate();
                return redirect('/gestao/dashboard');
            } elseif ($establishments->count() > 1) {
                session(['guard' => 'user']);
                $request->session()->regenerate();
                return redirect()->route('select.establishment');
            } else {
                Auth::guard('user')->logout();
                return redirect()->route('user.login')->with('error', 'Nenhum estabelecimento habilitado. Verifique o status do contrato do sistema.');
            }
        }

        return redirect()->route('user.login')->with('error', 'Credenciais inválidas.');
    }

    public function logout(Request $request)
    {
        Auth::guard('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }
}
