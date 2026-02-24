<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialities = Speciality::all();
        return view('admin.doctors.create', compact('specialities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // User validation
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',

            // Doctor validation
            'speciality_id' => 'required|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255|unique:doctors,medical_license_number',
            'biography' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            // Sync roles to ensure only 'Doctor' is assigned (removes default 'Paciente' if observer added it)
            $user->syncRoles('Doctor');

            // Create Doctor Profile
            Doctor::create([
                'user_id' => $user->id,
                'speciality_id' => $request->speciality_id,
                'medical_license_number' => $request->medical_license_number ?? 'N/A',
                'biography' => $request->biography,
            ]);
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $specialities = Speciality::all();
        return view('admin.doctors.edit', compact('doctor', 'specialities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'speciality_id' => 'required|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255|unique:doctors,medical_license_number,' . $doctor->id,
            'biography' => 'nullable|string',
        ]);

        $doctor->update([
            'speciality_id' => $request->speciality_id,
            'medical_license_number' => $request->medical_license_number ?? 'N/A',
            'biography' => $request->biography,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Información del doctor actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        Log::info('=== INICIO ELIMINACION DOCTOR ===', ['doctor_id' => $doctor->id, 'user_id' => $doctor->user_id]);

        $user = $doctor->user;

        DB::beginTransaction();

        try {
            Log::info('Iniciando transacción de eliminación de doctor', ['doctor_id' => $doctor->id]);

            // 1. Cargar relaciones necesarias
            $doctor->load('user');

            // 2. Eliminar citas asociadas al doctor
            $appointmentsCount = $doctor->appointments()->count();
            Log::info('Eliminando citas del doctor', ['count' => $appointmentsCount, 'doctor_id' => $doctor->id]);
            $doctor->appointments()->delete();

            // 3. Eliminar el doctor usando el modelo
            Log::info('Eliminando registro de doctor', ['doctor_id' => $doctor->id]);
            $doctorDeleted = $doctor->delete();

            if (!$doctorDeleted) {
                throw new \Exception('No se pudo eliminar el doctor del modelo');
            }

            // 4. Eliminar roles asociados
            if ($user) {
                Log::info('Eliminando roles del usuario', ['user_id' => $user->id]);
                $user->roles()->detach();

                // 5. Finalmente eliminar el usuario usando el modelo
                Log::info('Eliminando usuario de tabla users', ['user_id' => $user->id]);
                $userDeleted = $user->delete();

                if (!$userDeleted) {
                    throw new \Exception('No se pudo eliminar el usuario del modelo');
                }
            }

            Log::info('Doctor y usuario eliminados exitosamente', ['doctor_id' => $doctor->id, 'user_id' => $user ? $user->id : 'null']);

            // Confirmar transacción
            DB::commit();

            $mensaje = 'Doctor eliminado correctamente.';
            if (request()->ajax()) {
                return response()->json(['message' => $mensaje], 200);
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Doctor eliminado',
                'text' => $mensaje,
            ]);

            return redirect()->route('admin.doctors.index');

        } catch (\Exception $e) {
            // Revertir transacción si hay error
            DB::rollBack();

            Log::error('ERROR AL ELIMINAR DOCTOR', [
                'doctor_id' => $doctor->id,
                'user_id' => $doctor->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMsg = 'Error al eliminar el doctor: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['message' => $errorMsg], 403);
            }

            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => $errorMsg,
            ]);

            return redirect()->back();
        }
    }
}
