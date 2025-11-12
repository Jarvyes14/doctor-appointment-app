<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:users,name']);

        User::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        session()->flash('swal',
            [
                'icon'=>'success',
                'title'=> 'Usuario creado correctamente',
                'text' => 'El rol ha sido creado exitosamente'

            ]);

        return redirect()->route('admin.users.index')->with('success', 'Role created successfully');
    }

    /**
    public function show(string $id)
    {
        //
    }


     * Show the form for editing the specified resource.
     */
    /**
    public function edit(string $id)
    {
        if ($id <= 4){
            session()->flash('swal',
                [
                    'icon'=>'error',
                    'title'=> 'Error',
                    'text' => 'No puedes editar este rol'

                ]);

            return redirect()->route('admin.users.index')->with('error', 'Access Denied.');

        }else{
            try {
                $role = Role::findOrFail($id);

                return view('admin.users.edit', [
                    'role' => $role,
                ]);

            } catch (ModelNotFoundException $e) {

                return redirect()->route('admin.users.index')->with('error', [
                    'icon' => 'error',
                    'title' => 'Rol no encontrado',
                    'text' => 'El rol que intentas editar no existe.'
                ]);
            }
        }
    }
     * Update the specified resource in storage.
     */
    /**
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $id,
        ]);

        try {
            $role = Role::findOrFail($id);

            if ($role -> name === $request -> name){

                session()->flash('swal',
                    [
                        'icon'=>'question',
                        'title'=> 'Sin cambios',
                        'text' => 'Ningun cambio fue realizado'

                    ]);

                return redirect()->route('admin.users.index')->with('success', 'Role edited successfully.');

            }else{
                $role->update($request->only('name'));

                session()->flash('swal',
                    [
                        'icon'=>'success',
                        'title'=> 'Rol editado correctamente',
                        'text' => 'El rol ha sido editado exitosamente'

                    ]);

                return redirect()->route('admin.users.index')->with('success', 'Role edited successfully.');
            }

        } catch (Exception $e) {

            Log::error("Error al actualizar el rol ID: {$id}. Mensaje: " . $e->getMessage());

            session()->flash('swal',
                [
                    'icon'=>'error',
                    'title' => 'Error al editar el Rol',
                    'text' => 'No se pudo editar el rol. Por favor, inténta de nuevo.'

                ]);

            return redirect()->back()->with('error', 'Hubo un error al intentar actualizar el rol. Por favor, inténtalo de nuevo.');
        }
    }


     * Remove the specified resource from storage.
     */
        /**
    public function destroy(string $id)
    {
        if ($id <= 4){
            session()->flash('swal',
                [
                    'icon'=>'error',
                    'title'=> 'Error',
                    'text' => 'No puedes editar este rol'

                ]);

            return redirect()->route('admin.users.index')->with('error', 'Access Denied.');

        }else{
            try {
                $role = Role::findOrFail($id);
                $role->delete();

                session()->flash('swal',
                    [
                        'icon'=>'success',
                        'title'=> 'Rol eliminado correctamente',
                        'text' => 'El rol ha sido eliminado exitosamente'

                    ]);

                return redirect()->route('admin.users.index')->with('success', 'Role eliminated successfully.');

            } catch (ModelNotFoundException $e) {

                session()->flash('swal',
                    [
                        'icon'=>'error',
                        'title' => 'Error al encontrar el Rol',
                        'text' => 'No se pudo encontrar el rol. Por favor, verifica e inténta de nuevo.'

                    ]);

                return redirect()->back()->with('error', 'Hubo un error al encontrar el rol. Por favor, verifica e inténtalo de nuevo.');


            } catch (Exception $e) {
                Log::error("Error al eliminar el rol ID: {$id}. Mensaje: " . $e->getMessage());

                session()->flash('swal',
                    [
                        'icon'=>'error',
                        'title' => 'Error al eliminar el Rol',
                        'text' => 'No se pudo eliminar el rol. Por favor, inténta de nuevo.'

                    ]);

                return redirect()->back()->with('error', 'Hubo un error al eliminar actualizar el rol. Por favor, inténtalo de nuevo.');


            }
        }
    }*/
}
