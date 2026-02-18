<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol creado correctamente',
            'text' => 'El rol ha sido creado exitosamente'
        ]);

        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        // Los 4 primeros roles son protegidos
        if ($role->id <= 4) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes editar este rol'
            ]);

            return redirect()->route('admin.roles.index');
        }

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        // Los 4 primeros roles son protegidos
        if ($role->id <= 4) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes editar este rol'
            ]);

            return redirect()->route('admin.roles.index');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        try {
            if ($role->name === $request->name) {
                session()->flash('swal', [
                    'icon' => 'question',
                    'title' => 'Sin cambios',
                    'text' => 'Ningún cambio fue realizado'
                ]);

                return redirect()->route('admin.roles.index');
            }

            $role->update($request->only('name'));

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Rol editado correctamente',
                'text' => 'El rol ha sido editado exitosamente'
            ]);

            return redirect()->route('admin.roles.index');

        } catch (Exception $e) {
            Log::error("Error al actualizar el rol ID: {$role->id}. Mensaje: " . $e->getMessage());

            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error al editar el Rol',
                'text' => 'No se pudo editar el rol. Por favor, inténta de nuevo.'
            ]);

            return redirect()->back();
        }
    }

    public function destroy(Role $role)
    {
        // Los 4 primeros roles son protegidos
        if ($role->id <= 4) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar este rol'
            ]);

            return redirect()->route('admin.roles.index');
        }

        try {
            // Verificar si hay usuarios con este rol
            if ($role->users()->count() > 0) {
                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => 'Rol en uso',
                    'text' => 'No se puede eliminar el rol porque tiene usuarios asociados.'
                ]);
                return redirect()->route('admin.roles.index');
            }

            $role->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Rol eliminado correctamente',
                'text' => 'El rol ha sido eliminado exitosamente'
            ]);

            return redirect()->route('admin.roles.index');

        } catch (Exception $e) {
            Log::error("Error al eliminar el rol ID: {$role->id}. Mensaje: " . $e->getMessage());

            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar el Rol',
                'text' => 'No se pudo eliminar el rol. Por favor, inténta de nuevo.'
            ]);

            return redirect()->back();
        }
    }
}
