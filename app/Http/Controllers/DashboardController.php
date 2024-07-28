<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use App\Models\Establishment;
use App\Models\Category;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $role;
    public function __construct()
    {
        $this->role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
    }

    public function userDashboard()
    {
        $establishments_active = 0;
        $establishments_inactive = 0;
        $students_active = 0;
        $students_inactive = 0;
        $total_mes = "0.000.000";
        $total_ano = "0.000.000";

        if ($this->role && in_array($this->role->name, ['superuser'])){
            $establishments_active = Establishment::where('active', 1)->count();
            $establishments_inactive = Establishment::where('active', 0)->count();
            $students_active = Student::where('active', 1)->count();
            $students_inactive = Student::where('active', 0)->count();
            $total_mes = "1.000.000";
            $total_ano = "1.000.000";
        } else {
            $establishments_active = Establishment::where('active', 1)->where('id', Session::get('establishment_id'))->count();
            $establishments_inactive = Establishment::where('active', 0)->where('id', Session::get('establishment_id'))->count();
            $students_active = Student::join('student_establishment', 'students.id', '=', 'student_establishment.student_id')->where('students.active', 1)->where('student_establishment.establishment_id', Session::get('establishment_id'))->count();
            $students_inactive = Student::join('student_establishment', 'students.id', '=', 'student_establishment.student_id')->where('students.active', 0)->where('student_establishment.establishment_id', Session::get('establishment_id'))->count();
            $total_mes = "1.000.000";
            $total_ano = "1.000.000";
        }

        return view('admin.dashboard', compact('establishments_active', 'establishments_inactive', 'students_active', 'students_inactive', 'total_mes', 'total_ano'));
    }

    public function studentDashboard()
    {
        return view('student.dashboard');
    }
    
}
