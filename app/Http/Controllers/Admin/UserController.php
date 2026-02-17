<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
            'role'     => 'required|string|exists:roles,name',
        ]);

        $role = Role::where('name', $validated['role'])->firstOrFail();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_number' => $validated['id_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        $user->assignRole($role->name);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario creado',
            'text'  => 'El usuario ha sido creado exitosamente.',
        ]);

        return redirect()->route('admin.usuarios.index');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        $bloodTypes = BloodType::all();

        // Debes incluir 'roles' y 'bloodTypes' en el array
        return view('admin.users.edit', [
            'user' => $usuario,
            'roles' => $roles,
            'bloodTypes' => $bloodTypes
        ]);
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
            'blood_type_id' => 'nullable|exists:blood_types,id',
        ]);

        $usuario->update($validated);
        $usuario->syncRoles([$validated['role']]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario actualizado',
            'text'  => 'El usuario ha sido actualizado exitosamente.',
        ]);

        return redirect()->route('admin.usuarios.index');
    }

    public function show(User $usuario)
    {
        $usuario->load('roles', 'bloodType');
        return view('admin.users.show', compact('usuario'));
    }

    public function destroy(User $usuario)
    {
        \Log::info('=== INICIO ELIMINACION USUARIO ===', ['user_id' => $usuario->id, 'user_email' => $usuario->email]);

        $ejecutor = auth()->user();

        // Validaciones previas
        if ($usuario->id === 1) {
            \Log::warning('Intento de eliminar admin principal');
            if (request()->ajax()) {
                return response()->json(['message' => 'No se puede eliminar al administrador principal.'], 403);
            }
            return redirect()->back()->with('error', 'No se puede eliminar al administrador principal.');
        }

        if ($usuario->id === $ejecutor->id) {
            \Log::warning('Intento de auto-eliminación');
            if (request()->ajax()) {
                return response()->json(['message' => 'No puedes eliminar tu propia cuenta.'], 403);
            }
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($usuario->hasRole('Administrador') && !$ejecutor->hasRole('Administrador')) {
            \Log::warning('Sin permisos para eliminar admin');
            if (request()->ajax()) {
                return response()->json(['message' => 'No tienes permisos para eliminar a un administrador.'], 403);
            }
            return redirect()->back()->with('error', 'No tienes permisos para eliminar a un administrador.');
        }

        // Usar transacción para asegurar que todo se ejecuta o nada
        \DB::beginTransaction();

        try {
            \Log::info('Iniciando transacción de eliminación', ['user_id' => $usuario->id]);

            // 1. Eliminar citas asociadas
            $appointmentsCount = $usuario->appointments()->count();
            \Log::info('Eliminando citas', ['count' => $appointmentsCount, 'user_id' => $usuario->id]);
            $usuario->appointments()->delete();

            // 2. Eliminar paciente si existe
            if ($usuario->patient) {
                \Log::info('Eliminando paciente', ['user_id' => $usuario->id]);
                $usuario->patient->delete();
            }

            // 3. Eliminar doctor si existe
            if ($usuario->doctor) {
                \Log::info('Eliminando doctor', ['user_id' => $usuario->id]);
                $usuario->doctor->delete();
            }

            // 4. Eliminar roles
            \Log::info('Desasociando roles', ['user_id' => $usuario->id]);
            $usuario->roles()->detach();

            // 5. Finalmente eliminar al usuario usando el modelo
            \Log::info('Eliminando usuario de tabla users', ['user_id' => $usuario->id]);
            $resultado = $usuario->delete();

            if (!$resultado) {
                throw new \Exception('No se pudo eliminar el usuario del modelo');
            }

            \Log::info('Usuario eliminado exitosamente', ['user_id' => $usuario->id]);

            // Confirmar transacción
            \DB::commit();

            $mensaje = 'Usuario eliminado correctamente.';
            if (request()->ajax()) {
                return response()->json(['message' => $mensaje], 200);
            }
            return redirect()->back()->with('success', $mensaje);

        } catch (\Exception $e) {
            // Revertir transacción si hay error
            \DB::rollBack();

            \Log::error('ERROR AL ELIMINAR USUARIO', [
                'user_id' => $usuario->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMsg = 'Error al eliminar el usuario: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['message' => $errorMsg], 403);
            }
            return redirect()->back()->with('error', $errorMsg);
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
