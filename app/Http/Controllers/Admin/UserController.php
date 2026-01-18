<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'id_number'=> 'nullable|string|max:20|unique:users,id_number',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'role'     => 'required|string|in:Administrador,Doctor,Recepcionista,Paciente',
        ]);

        $role = $validated['role'];
        unset($validated['role']);
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->assignRole($role);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario creado',
            'text'  => 'El usuario ha sido creado exitosamente.',
        ]);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        return view('admin.users.edit', ['user' => $usuario]);
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => 'nullable|min:6|confirmed',
            'id_number'=> ['nullable', 'string', 'max:20', Rule::unique('users', 'id_number')->ignore($usuario->id)],
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'role'     => 'required|string|in:Administrador,Doctor,Recepcionista,Paciente',
        ]);

        // Si se proporciona una contraseña, encriptarla; si no, eliminarla del array
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $role = $validated['role'];
        unset($validated['role']);

        $usuario->update($validated);

        // Sincronizar rol (elimina roles anteriores y asigna el nuevo)
        $usuario->syncRoles([$role]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario actualizado',
            'text'  => 'El usuario ha sido actualizado exitosamente.',
        ]);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $usuario)
    {
        // Evitar eliminar al administrador principal
        if ($usuario->id === 1) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se puede eliminar al administrador principal.',
            ]);

            return redirect()->route('admin.users.index');
        }

        // Evitar que un usuario se elimine a sí mismo
        if ($usuario->id === auth()->id()) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No puedes eliminarte a ti mismo.',
            ]);

            return redirect()->route('admin.users.index');
        }

        $usuario->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario eliminado',
            'text'  => 'El usuario ha sido eliminado exitosamente.',
        ]);

        return redirect()->route('admin.users.index');
    }
}
