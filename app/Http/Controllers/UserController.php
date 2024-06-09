<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        $establishments = \App\Models\Establishment::all();
        $roles = Role::where('role', '!=', 'superuser')->get();

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

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit($user)
    {
        $user = User::with('establishments')->find($user);
        $establishments = \App\Models\Establishment::all();
        $roles = Role::where('role', '!=', 'superuser')->get();

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

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function view($userId)
    {
        $user = User::findOrFail($userId);
        $establishments = \App\Models\Establishment::all();
        $roles = Role::where('role', '!=', 'superuser')->get();
        
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

        return redirect()->route('admin.users.index')->with('success', 'Usuário restaurado com sucesso.');
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

}
