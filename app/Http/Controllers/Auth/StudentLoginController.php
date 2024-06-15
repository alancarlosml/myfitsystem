<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            return redirect()->intended('/app/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não estão corretas',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        return redirect()->route('student.login');
    }
}
