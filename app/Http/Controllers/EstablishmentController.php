<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstablishmentRequest;
use App\Http\Requests\UpdateEstablishmentRequest;
use App\Models\Establishment;
use App\Models\Role;
use App\Models\Student;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstablishmentController extends Controller
{
    public function index()
    {
        $establishments = Establishment::all();
        return view('admin.establishments.index', ['establishments' => $establishments]);
    }

    public function create()
    {
        $types = Establishment::pluck('type', 'type')->unique();
        return view('admin.establishments.add', ['types'=> $types]);
    }

    public function store(StoreEstablishmentRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        Establishment::create($validatedData);

        return redirect()->route('admin.establishments.index')->with('success', 'Estabelecimento criado com sucesso!');
    }

    public function edit($establishment)
    {
        $establishment = Establishment::find($establishment);
        $types = Establishment::pluck('type', 'type')->unique();

        return view('admin.establishments.edit', ['establishment' => $establishment, 'types' => $types]);
    }

    public function update(UpdateEstablishmentRequest $request, $establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        $establishment->update($validatedData);

        return redirect()->route('admin.establishments.index')->with('success', 'Estabelecimento atualizado com sucesso!');
    }
    public function manage($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        
        return view('admin.establishments.manage', ['establishment' => $establishment]);
    }

    public function view($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        
        return view('admin.establishments.manage_view', ['establishment' => $establishment]);
    }

    public function students($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        
        return view('admin.establishments.manage_students', ['establishment' => $establishment]);
    }

    public function users($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        $roles = Role::where('name', '!=', 'superuser')->get();
        
        return view('admin.establishments.manage_users', ['establishment' => $establishment, 'roles' => $roles]);
    }

    public function contracts($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        
        return view('admin.establishments.manage_contracts', ['establishment' => $establishment]);
    }

    public function contractStore(Request $request, $establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);

        $validatedData = $request->validate([
            'service_name' => 'required|in:semanal,mensal,trimestral,semestral,anual',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_type' => 'required|in:credito,debito,pix,boleto,dinheiro',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'active' => 'boolean',
        ]);

        $contract = $establishment->contracts()->create([
            'service_name' => $validatedData['service_name'],
            'amount' => $validatedData['amount'],
            'payment_date' => $validatedData['payment_date'],
            'payment_type' => $validatedData['payment_type'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'active' => $validatedData['active'] ?? true,
        ]);

        // Redirect back to a relevant page with a success message
        return redirect()->back()->with('success', 'Contrato criado com sucesso');
    }

    public function destroy($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        $establishment->delete();
    }

    public function restore($establishmentId)
    {
        $establishment = Establishment::withTrashed()->findOrFail($establishmentId);
        $establishment->restore();

        return redirect()->route('admin.establishments.index')->with('success', 'Estabelecimento restaurado com sucesso.');
    }

    public function selectEstablishment()
    {
        $guard = Auth::guard('user')->check() ? 'user' : 'student';
        $user = Auth::guard($guard)->user();
        $establishments = [];
        
        if($guard == 'user') {
            $establishments = $user->getEstablishmentsActive()->with('roles')->get();
        } else {
            $student = Student::where('id', $user->id)->first();
            $establishments = $student->establishments()->get();
        }

        // Para cada estabelecimento, obtenha o papel do usuário
        foreach ($establishments as $establishment) {
            $role = $user->getRoleForEstablishment($establishment->id);
            $establishment->role_name = $role ? $role->name : 'Nenhum papel';
        }

        return view('auth.select-establishment', compact('establishments'));
    }

    public function storeEstablishment(Request $request)
    {
        $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
        ]);

        // Armazenar o estabelecimento na sessão
        session(['establishment_id' => $request->establishment_id]);

        $guard = Auth::guard('user')->check() ? 'user' : 'student';
        $redirect = '/';
        if($guard == 'user') {
            $redirect = '/gestao/dashboard';
        } else {
            $redirect = '/app/dashboard';
        }

        return redirect()->intended($redirect);
    }

}
