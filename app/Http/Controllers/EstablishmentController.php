<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstablishmentRequest;
use App\Http\Requests\UpdateEstablishmentRequest;
use App\Models\Establishment;
use App\Models\Role;
use App\Models\Student;
use App\Services\AccessControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstablishmentController extends Controller
{
    protected AccessControlService $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    public function index(Request $request)
    {
        $query = Establishment::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status == 'ativo' ? 1 : 0);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date range filter
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $establishments = $query->orderBy('name')->get();
        
        // Get unique types for filter
        $types = Establishment::distinct()->whereNotNull('type')->pluck('type')->unique();
        
        // Count establishments with pending contracts (pendente or vencido)
        $establishmentsWithPendingContracts = Establishment::whereHas('contracts', function($q) {
            $q->whereIn('status', ['pendente', 'vencido'])
              ->where('active', 1);
        })->count();
        
        return view('admin.establishments.index', [
            'establishments' => $establishments,
            'types' => $types,
            'filters' => $request->only(['search', 'status', 'type', 'created_from', 'created_to']),
            'establishmentsWithPendingContracts' => $establishmentsWithPendingContracts
        ]);
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
        $users = $establishment->users()->paginate(10);
        $roles = Role::where('name', '!=', 'superuser')->get();

        return view('admin.establishments.manage_users', ['establishment' => $establishment, 'users' => $users, 'roles' => $roles]);
    }

    public function contracts($establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);
        
        return view('admin.establishments.manage_contracts', ['establishment' => $establishment]);
    }

    public function contractStore(Request $request, $establishmentId)
    {
        $establishment = Establishment::findOrFail($establishmentId);

        // Prepara os dados antes da validação
        $data = $request->all();
        
        // Converte o valor monetário formatado (R$ 1.234,56) para número
        if (isset($data['amount'])) {
            $data['amount'] = str_replace(['R$', ' ', '.'], '', $data['amount']);
            $data['amount'] = str_replace(',', '.', $data['amount']);
            $request->merge(['amount' => $data['amount']]);
        }

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
        $actor = Auth::guard($guard)->user();

        if ($guard === 'user' && $this->accessControlService->userIsSuperuser($actor)) {
            $establishments = Establishment::orderBy('name')->get()->map(function (Establishment $establishment) {
                $establishment->role_name = 'superuser';
                $establishment->contract_active = $this->accessControlService->establishmentHasActiveSystemContract($establishment);
                return $establishment;
            });
        } elseif ($guard === 'user') {
            $establishments = $this->accessControlService
                ->getAccessibleEstablishmentsForUser($actor)
                ->map(function (Establishment $establishment) use ($actor) {
                    $role = $actor->getRoleForEstablishment($establishment->id);
                    $establishment->role_name = $role ? $role->name : 'Nenhum papel';
                    $establishment->contract_active = true;
                    return $establishment;
                });
        } else {
            /** @var Student $actor */
            $establishments = $this->accessControlService
                ->getAccessibleEstablishmentsForStudent($actor)
                ->map(function (Establishment $establishment) {
                    $establishment->role_name = 'Aluno';
                    $establishment->contract_active = true;
                    return $establishment;
                });
        }

        return view('auth.select-establishment', compact('establishments'));
    }

    public function storeEstablishment(Request $request)
    {
        $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
        ]);

        // Armazenar o estabelecimento na sessão
        $guard = Auth::guard('user')->check() ? 'user' : 'student';

        if ($guard === 'user') {
            $user = Auth::guard('user')->user();

            if (!$this->accessControlService->userIsSuperuser($user)) {
                $allowed = $this->accessControlService
                    ->getAccessibleEstablishmentsForUser($user)
                    ->pluck('id');

                if (!$allowed->contains($request->establishment_id)) {
                    return redirect()->route('select.establishment')->with('error', 'Estabelecimento indisponível para o seu acesso.');
                }
            }
        } else {
            $student = Auth::guard('student')->user();
            $allowed = $this->accessControlService
                ->getAccessibleEstablishmentsForStudent($student)
                ->pluck('id');

            if (!$allowed->contains($request->establishment_id)) {
                return redirect()->route('select.establishment')->with('error', 'Estabelecimento indisponível. Regularize seu contrato.');
            }
        }

        session(['establishment_id' => $request->establishment_id]);

        $redirect = $guard === 'user' ? '/gestao/dashboard' : '/app/dashboard';

        return redirect()->intended($redirect);
    }

}
