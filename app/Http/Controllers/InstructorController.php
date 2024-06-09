<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Establishment;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class InstructorController extends Controller
{
    public function index()
    {
        $users = User::whereHas('establishments', function($query) {
            $query->where('role', '=', 'instrutor');
        })->get();

        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        $establishments = Establishment::all();

        return view('admin.users.add', ['establishments' => $establishments]);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        $instructorData = [
            'profile_picture' => $request->input('profile_picture'),
            'academic_degree' => $request->input('academic_degree'),
            'professional_experience' => $request->input('professional_experience'),
        ];

        $user->instructor()->create($instructorData);

        if ($request->has('establishments')) {
            $user->establishments()->attach($request->input('establishments'), ['role' => 'instrutor']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit($user)
    {
        $user = User::with(['establishments', 'instructor'])->findOrFail($user);
        $establishments = Establishment::all();

        return view('admin.users.edit', ['user' => $user, 'establishments' => $establishments]);
    }

    public function update(UpdateUserRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validatedData = $request->validated();

        $user->update($validatedData);

        $instructorData = [
            'profile_picture' => $request->input('profile_picture'),
            'academic_degree' => $request->input('academic_degree'),
            'professional_experience' => $request->input('professional_experience'),
        ];

        $user->instructor()->update($instructorData);

        if ($request->has('establishments')) {
            $user->establishments()->sync($request->input('establishments'), ['role' => 'instrutor']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function view($userId)
    {
        $user = User::findOrFail($userId);
        
        return view('admin.users.view', ['user' => $user]);
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
    }

    public function restore($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'Usuário restaurado com sucesso.');
    }
}
