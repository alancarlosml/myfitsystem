<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Establishment;
use App\Models\Student;
use App\Models\StudentContracts;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = \App\Models\Student::all();
        return view('admin.students.index', ['students' => $students]);
    }

    public function create()
    {
        return view('admin.students.add');
    }

    public function store(StoreStudentRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        \App\Models\Student::create($validatedData);

        return redirect()->route('admin.students.index')->with('success', 'Aluno criado com sucesso!');
    }


    public function edit($student)
    {
        $student = \App\Models\Student::find($student);
        $genders = \App\Models\Student::pluck('gender', 'gender')->unique();

        return view('admin.students.edit', ['student' => $student, 'genders' => $genders]);
    }

    public function update(UpdateStudentRequest $request, $studentId)
    {
        $student = \App\Models\Student::findOrFail($studentId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        $student->update($validatedData);

        return redirect()->route('admin.students.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function view($studentId)
    {
        $student = \App\Models\Student::findOrFail($studentId);
        
        return view('admin.students.view', ['student' => $student]);
    }


    public function destroy($studentId)
    {
        $student = \App\Models\Student::findOrFail($studentId);
        $student->delete();
    }

    public function restore($studentId)
    {
        $student = \App\Models\Student::withTrashed()->findOrFail($studentId);
        $student->restore();

        return redirect()->route('admin.students.index')->with('success', 'Aluno restaurado com sucesso.');
    }

    public function contracts($studentId, $establishmentId){

        $student = \App\Models\Student::findOrFail($studentId);
        $establishment = \App\Models\Establishment::findOrFail($establishmentId);

        $contracts = \App\Models\StudentContracts::where('student_id', $studentId)->where('establishment_id', $establishmentId)->get();

        return view('admin.students.contracts', ['student' => $student, 'establishment' => $establishment, 'contracts' => $contracts]);

    }

    public function contractStore(Request $request, $studentId, $establishmentId)
    {
        // Valida os dados da requisição
        $validatedData = $request->validate([
            'service_name' => 'required|in:semanal,mensal,trimestral,semestral,anual',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_type' => 'required|in:credito,debito,pix,boleto,dinheiro',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'active' => 'nullable|boolean',
        ]);

        // Encontra o aluno e o estabelecimento
        $student = Student::findOrFail($studentId);
        $establishment = Establishment::findOrFail($establishmentId);

        // Cria um novo contrato
        $contract = new StudentContracts([
            'service_name' => $validatedData['service_name'],
            'amount' => $validatedData['amount'],
            'payment_date' => $validatedData['payment_date'],
            'payment_type' => $validatedData['payment_type'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'active' => $validatedData['active'] ?? true,
        ]);

        // Salva o contrato para o aluno no estabelecimento
        $contract->student()->associate($student);
        $contract->establishment()->associate($establishment);
        $contract->save();

        // Redirect back to a relevant page with a success message
        return redirect()->back()->with('success', 'Contrato criado com sucesso');
    }
}
