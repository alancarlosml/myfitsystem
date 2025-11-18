<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Models\Establishment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HasEstablishmentContext;

    public function index(Request $request)
    {
        $query = User::query();
        
        // Superuser can see all users, others filter by establishment
        if ($this->hasAnyRole(['superuser'])) {
            $query->with('roles')->orderBy('name');
        } else {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                // Buscar IDs dos usuários vinculados ao estabelecimento
                $userIds = DB::table('role_user')
                    ->where('establishment_id', $establishmentId)
                    ->pluck('user_id')
                    ->unique();
                
                $query->whereIn('id', $userIds)
                    ->whereNull('deleted_at')
                    ->orderBy('name');
                
            } else {
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

        // Date range filter
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $users = $query->get();
        
        // Adicionar o papel de cada usuário no estabelecimento atual (para não-superuser)
        if (!$this->hasAnyRole(['superuser'])) {
            $establishmentId = $this->getEstablishmentId();
            if ($establishmentId) {
                $users->each(function ($user) use ($establishmentId) {
                    $role = $user->getRoleForEstablishment($establishmentId);
                    $user->role_name = $role ? $role->name : 'Sem papel';
                });
            }
        }
        
        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'status', 'created_from', 'created_to'])
        ]);
    }

    public function create()
    {
        $establishments = Establishment::all();
        $roles = Role::where('name', '!=', 'superuser')->get();

        return view('admin.users.add', ['establishments' => $establishments, 'roles'=> $roles]);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        $user = User::create($validatedData);

        // Vincular usuário ao estabelecimento e papel
        if (!$this->hasAnyRole(['superuser'])){
            $establishmentId = $this->getEstablishmentId();
            $roleId = $request->input('role');
            
            if ($establishmentId && $roleId) {
                $user->roles()->attach($roleId, ['establishment_id' => $establishmentId]);
            }
        } else {
            // Para superuser, usar os valores do formulário se fornecidos
            $establishmentId = $request->input('establishment');
            $roleId = $request->input('role');
            
            if ($establishmentId && $roleId) {
                $user->roles()->attach($roleId, ['establishment_id' => $establishmentId]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Colaborador criado com sucesso!');
    }

    public function edit($user)
    {
        $user = User::with('establishments')->find($user);
        $establishments = Establishment::all();
        $roles = Role::where('name', '!=', 'superuser')->get();

        return view('admin.users.edit', ['user' => $user, 'establishments' => $establishments, 'roles' => $roles]);
    }

    public function update(UpdateUserRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        if (!$this->hasAnyRole(['superuser'])){
            $validatedData['establishment_id'] = $this->getEstablishmentId();
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Colaborador atualizado com sucesso!');
    }

    public function view($userId)
    {
        $user = User::findOrFail($userId);
        $establishments = Establishment::all();
        $roles = Role::where('name', '!=', 'superuser')->get();
        
        return view('admin.users.view', ['user' => $user, 'establishments' => $establishments, 'roles' => $roles]);
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
    }

    public function restore($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'Colaborador restaurado com sucesso.');
    }

    public function linkEstablishment(Request $request, $userId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'establishment_id' => 'required|exists:establishments,id',
            'active' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->roles()->attach($request->role_id, ['establishment_id' => $request->establishment_id]);

        return redirect()->route('admin.users.view', ['user' => $user])->with('success', 'Papel atribuído com sucesso')->withFragment('#user-establishments');
        #return response()->json(['message' => 'Papel atribuído com sucesso']);
    }

    public function updateLinkEstablishment(Request $request, $userId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'establishment_id' => 'required|exists:establishments,id',
            'active' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($request->user_id);

        #dd($request->role_id, $request->establishment_id);

        // $user->roles()->detach($request->role_id);
        // $user->roles()->attach($request->role_id, ['establishment_id' => $request->establishment_id]);
        
        //$user->roles()->updateExistingPivot($request->establishment_id, ['role_id' => $request->role_id]);

        $user->roles()->wherePivot('establishment_id', $request->establishment_id)->update(['role_id' => $request->role_id]);

        return redirect()->back()->with('success', 'Papel atualizado com sucesso')->withFragment('#user-establishments');
    }

    public function unlinkEstablishment(Request $request, $userId, $establishmentId)
    {
        // Retrieve the user instance, fail if not found
        $user = User::findOrFail($userId);

        // Detach the specific establishment from the user's roles
        $user->roles()->wherePivot('establishment_id', $establishmentId)->detach();

        // Redirect back to the user view with a success message and fragment identifier
        return redirect()->route('admin.users.view', ['user' => $userId])
                        ->with('success', 'Papel removido com sucesso')
                        ->withFragment('#user-establishments');
    }

    // System-wide user management for superuser
    public function systemUsers()
    {
        $users = User::with(['roles', 'establishments'])->get();
        return view('admin.system_users.index', ['users' => $users]);
    }

    public function createSystemUser()
    {
        $establishments = Establishment::all();
        $roles = Role::all();

        return view('admin.system_users.add', ['establishments' => $establishments, 'roles' => $roles]);
    }

    public function storeSystemUser(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        $user = User::create($validatedData);

        return redirect()->route('admin.system_users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function editSystemUser($user)
    {
        $user = User::with(['roles', 'establishments'])->find($user);
        $establishments = Establishment::all();
        $roles = Role::all();

        return view('admin.system_users.edit', ['user' => $user, 'establishments' => $establishments, 'roles' => $roles]);
    }

    public function updateSystemUser(UpdateUserRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        $user->update($validatedData);

        return redirect()->route('admin.system_users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function viewSystemUser($userId)
    {
        $user = User::with(['roles', 'establishments'])->findOrFail($userId);
        $establishments = Establishment::all();
        $roles = Role::all();

        return view('admin.system_users.view', ['user' => $user, 'establishments' => $establishments, 'roles' => $roles]);
    }

    public function destroySystemUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect()->route('admin.system_users.index')->with('success', 'Usuário removido com sucesso!');
    }

}
