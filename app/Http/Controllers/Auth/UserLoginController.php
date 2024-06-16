<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('user')->attempt($credentials)) {
            $user = Auth::guard('user')->user();

            // Verifica se o usuário é superuser
            // if ($user->is_superuser) {
            //     session(['establishment_id' => null, 'guard' => 'user']); // Definir establishment_id como null para superusers
            //     return redirect()->intended('/gestao/dashboard');
            // }


            $establishments = $user->establishments()->get();
            
            if ($establishments->count() == 1) {
                $establishment = $establishments->first();
                session(['establishment_id' => $establishment->id, 'guard' => 'user']);
                return redirect()->intended('/gestao/dashboard');
            } elseif ($establishments->count() > 1) {
                session(['guard' => 'user']);
                return redirect()->route('select.establishment');
            } else {
                Auth::guard('user')->logout();
                return redirect()->route('user.login')->with('error', 'Você não tem acesso a nenhum estabelecimento.');
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
