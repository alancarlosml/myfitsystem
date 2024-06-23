<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
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
            $user = Auth::guard('student')->user();
            $student = Student::where('id', $user->id)->first();
            $establishments = $student->establishments()->get();
            
            if ($establishments->count() == 1) {
                $establishment = $establishments->first();
                session(['establishment_id' => $establishment->id, 'guard' => 'student']);
                return redirect()->intended('/app/dashboard');
            } elseif ($establishments->count() > 1) {
                session(['guard' => 'student']);
                return redirect()->route('select.establishment');
            } else {
                Auth::guard('student')->logout();
                return redirect()->route('student.login')->with('error', 'Você não tem acesso a nenhum estabelecimento.');
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
