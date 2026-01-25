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

        // Evitar quitarse el rol de administrador a uno mismo
        if ($usuario->id === auth()->id() && $request->role !== 'Administrador') {
            session()->flash('swal', [
                'icon'  => 'warning',
                'title' => 'Acción no permitida',
                'text'  => 'No puedes quitarte el rol de Administrador a ti mismo para no perder acceso.',
            ]);
            return redirect()->back();
        }

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
        // 1. Forzar la carga de roles para evitar fallos en la validación
        $usuario->load('roles');
        $ejecutor = auth()->user();

        // 2. Validación: Administrador Principal (ID 1)
        if ($usuario->id === 1) {
            return $this->errorResponse('No se puede eliminar al administrador principal.');
        }

        // 3. Validación: Autosuicidio
        if ($usuario->id === $ejecutor->id) {
            return $this->errorResponse('No puedes eliminar tu propia cuenta.');
        }

        // 4. Validación: Jerarquía (Un no-admin intentando borrar a un Admin)
        if ($usuario->hasRole('Administrador') && !$ejecutor->hasRole('Administrador')) {
            return $this->errorResponse('No tienes permisos para eliminar a un administrador.');
        }

        // 5. Intento de eliminación real con manejo de errores de DB
        try {
            $usuario->delete();

            $mensaje = 'Usuario eliminado correctamente.';
            return request()->ajax()
                ? response()->json(['message' => $mensaje], 200)
                : redirect()->back()->with('swal', ['icon' => 'success', 'text' => $mensaje]);

        } catch (\Exception $e) {
            return $this->errorResponse('El usuario tiene registros vinculados (citas, historial) y no puede ser eliminado.');
        }
    }

// Función auxiliar para no repetir código de error
    private function errorResponse($mensaje) {
        if (request()->ajax()) {
            return response()->json(['message' => $mensaje], 403);
        }
        return redirect()->back()->with('swal', ['icon' => 'error', 'text' => $mensaje]);
    }
}
