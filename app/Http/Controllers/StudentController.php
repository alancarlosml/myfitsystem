<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Models\Establishment;
use App\Models\Student;
use App\Models\StudentContracts;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    use HasEstablishmentContext;

    public function index(Request $request)
    {
        $query = Student::query();
        
        // Superuser can see all students, others filter by establishment
        if ($this->hasAnyRole(['superuser'])) {
            // Filter by establishment if provided
            if ($request->filled('establishment_id')) {
                $query->whereHas('establishments', function($q) use ($request) {
                    $q->where('establishments.id', $request->establishment_id);
                });
            }
        } else {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                // Optimized: Use join instead of whereHas for better performance
                $query->join('student_establishment', 'students.id', '=', 'student_establishment.student_id')
                    ->where('student_establishment.establishment_id', $establishmentId)
                    ->select('students.*')
                    ->distinct();
            } else {
                // No establishment selected, return empty
                $query->whereRaw('1 = 0');
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status == 'ativo' ? 1 : 0);
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Date range filter
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $students = $query->get();
        
        // Get establishments for filter dropdown (superuser only)
        $establishments = collect([]);
        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::orderBy('name')->get();
        }

        // Get unique genders for filter
        $genders = Student::distinct()->whereNotNull('gender')->pluck('gender')->map(function($gender) {
            return [
                'value' => $gender,
                'label' => ucfirst($gender)
            ];
        });

        return view('admin.students.index', [
            'students' => $students,
            'establishments' => $establishments,
            'genders' => $genders,
            'filters' => $request->only(['search', 'status', 'gender', 'establishment_id', 'created_from', 'created_to'])
        ]);
    }

    public function create()
    {
        $genders = ['masculino', 'feminino', 'outro'];
        return view('admin.students.add', ['genders' => $genders]);
    }

    public function store(StoreStudentRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        // Gera uma senha padrão se não fornecida
        if (empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt('123456'); // Senha padrão temporária
        } else {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $student = Student::create($validatedData);

        // Vincular aluno ao estabelecimento do admin logado (se não for superuser)
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                $student->establishments()->attach($establishmentId, ['active' => true]);
            }
        }

        return redirect()->route('admin.students.index')->with('success', 'Aluno criado com sucesso!');
    }


    public function edit($student)
    {
        $student = Student::find($student);
        $genders = ['masculino', 'feminino', 'outro'];

        return view('admin.students.edit', ['student' => $student, 'genders' => $genders]);
    }

    public function update(UpdateStudentRequest $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        // Se a senha foi fornecida, criptografa ela
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            // Remove a senha do array se não foi fornecida
            unset($validatedData['password']);
        }

        $student->update($validatedData);

        return redirect()->route('admin.students.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function view($studentId)
    {
        $student = Student::findOrFail($studentId);
        
        return view('admin.students.view', ['student' => $student]);
    }


    public function destroy($studentId)
    {
        $student = Student::findOrFail($studentId);
        $student->delete();
    }

    public function restore($studentId)
    {
        $student = Student::withTrashed()->findOrFail($studentId);
        $student->restore();

        return redirect()->route('admin.students.index')->with('success', 'Aluno restaurado com sucesso.');
    }

    public function contracts($studentId, $establishmentId){

        $student = Student::findOrFail($studentId);
        $establishment = Establishment::findOrFail($establishmentId);

        $contracts = StudentContracts::where('student_id', $studentId)->where('establishment_id', $establishmentId)->get();

        return view('admin.students.contracts', ['student' => $student, 'establishment' => $establishment, 'contracts' => $contracts]);

    }

    public function contractStore(Request $request, $studentId, $establishmentId)
    {
        // Prepara os dados antes da validação
        $data = $request->all();
        
        // Converte o valor monetário formatado (R$ 1.234,56) para número
        if (isset($data['amount'])) {
            $data['amount'] = str_replace(['R$', ' ', '.'], '', $data['amount']);
            $data['amount'] = str_replace(',', '.', $data['amount']);
            $request->merge(['amount' => $data['amount']]);
        }

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
