<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PatientsController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user', 'bloodType')->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        $bloodTypes = BloodType::all();
        return view('admin.patients.create', compact('bloodTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'id_number' => 'nullable|string|max:20|unique:users,id_number',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'id_number' => $validated['id_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'blood_type_id' => $validated['blood_type_id'] ?? null,
        ]);

        // Asignar rol de Paciente
        $user->assignRole('Paciente');

        // Crear el registro de paciente
        Patient::create([
            'user_id' => $user->id,
            'blood_type_id' => $validated['blood_type_id'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente creado',
            'text' => 'El paciente ha sido creado exitosamente.',
        ]);

        return redirect()->route('admin.patients.index');
    }

    public function show(Patient $patient)
    {
        $patient->load('user', 'bloodType');
        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();
        $patient->load('user');

        // Determinar la pestaña inicial basada en errores de validación
        $initialTab = $this->determineInitialTab();

        return view('admin.patients.edit', compact('patient', 'bloodTypes', 'initialTab'));
    }

    /**
     * Determina qué pestaña debe abrirse al cargar la página basándose en errores de validación
     */
    private function determineInitialTab()
    {
        // Obtener el bag de errores de la sesión
        $errorBag = session()->get('errors');

        // Si no hay errores, retornar la primera pestaña
        if (!$errorBag || !method_exists($errorBag, 'any') || !$errorBag->any()) {
            return 'usuario';
        }

        // Campos por pestaña
        $tabFields = [
            'usuario' => ['name', 'email', 'id_number', 'phone', 'address'],
            'personales' => ['date_of_birth', 'gender', 'occupation', 'blood_type_id', 'allergies'],
            'medicos' => ['medical_history', 'family_medical_history', 'chronic_diseases', 'medications'],
            'salud' => ['general_health_notes', 'smoker', 'drinker'],
            'emergencia' => ['emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone'],
        ];

        // Encontrar la primera pestaña con errores
        foreach ($tabFields as $tab => $fields) {
            foreach ($fields as $field) {
                if ($errorBag->has($field)) {
                    return $tab;
                }
            }
        }

        return 'usuario'; // Default
    }

    public function update(Request $request, Patient $patient)
    {
        $user = $patient->user;

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'id_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'id_number')->ignore($user->id)],
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'blood_type_id' => 'nullable|exists:blood_types,id',
                'medical_history' => 'nullable|string',
                'allergies' => 'nullable|string',
                // Datos Personales
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:Masculino,Femenino,Otro',
                'occupation' => 'nullable|string|max:255',
                // Antecedentes
                'family_medical_history' => 'nullable|string',
                'chronic_diseases' => 'nullable|string',
                'medications' => 'nullable|string',
                // Información General
                'general_health_notes' => 'nullable|string',
                'smoker' => 'nullable|boolean',
                'drinker' => 'nullable|boolean',
                // Contacto de Emergencia
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_relationship' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirigir de vuelta con los errores
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        // Actualizar usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_number' => $validated['id_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'blood_type_id' => $validated['blood_type_id'] ?? null,
        ]);

        // Actualizar paciente
        $patient->update([
            'blood_type_id' => $validated['blood_type_id'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'family_medical_history' => $validated['family_medical_history'] ?? null,
            'chronic_diseases' => $validated['chronic_diseases'] ?? null,
            'medications' => $validated['medications'] ?? null,
            'general_health_notes' => $validated['general_health_notes'] ?? null,
            'smoker' => $validated['smoker'] ?? false,
            'drinker' => $validated['drinker'] ?? false,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente actualizado',
            'text' => 'El paciente ha sido actualizado exitosamente.',
        ]);

        return redirect()->route('admin.patients.index');
    }

    public function destroy(Patient $patient)
    {
        \Log::info('=== INICIO ELIMINACION PACIENTE ===', ['patient_id' => $patient->id, 'user_id' => $patient->user_id]);

        $user = $patient->user;

        \DB::beginTransaction();

        try {
            \Log::info('Iniciando transacción de eliminación de paciente', ['patient_id' => $patient->id]);

            // 1. Cargar relaciones necesarias
            $patient->load('user');

            // 2. Eliminar citas asociadas al paciente
            $appointmentsCount = $user->appointments()->count();
            \Log::info('Eliminando citas del paciente', ['count' => $appointmentsCount, 'user_id' => $user->id]);
            $user->appointments()->delete();

            // 3. Eliminar el paciente usando el modelo
            \Log::info('Eliminando registro de paciente', ['patient_id' => $patient->id]);
            $patientDeleted = $patient->delete();

            if (!$patientDeleted) {
                throw new \Exception('No se pudo eliminar el paciente del modelo');
            }

            // 4. Eliminar roles asociados
            \Log::info('Eliminando roles del usuario', ['user_id' => $user->id]);
            $user->roles()->detach();

            // 5. Finalmente eliminar el usuario usando el modelo
            \Log::info('Eliminando usuario de tabla users', ['user_id' => $user->id]);
            $userDeleted = $user->delete();

            if (!$userDeleted) {
                throw new \Exception('No se pudo eliminar el usuario del modelo');
            }

            \Log::info('Paciente y usuario eliminados exitosamente', ['patient_id' => $patient->id, 'user_id' => $user->id]);

            // Confirmar transacción
            \DB::commit();

            $mensaje = 'Paciente eliminado correctamente.';
            if (request()->ajax()) {
                return response()->json(['message' => $mensaje], 200);
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Paciente eliminado',
                'text' => $mensaje,
            ]);

            return redirect()->route('admin.patients.index');

        } catch (\Exception $e) {
            // Revertir transacción si hay error
            \DB::rollBack();

            \Log::error('ERROR AL ELIMINAR PACIENTE', [
                'patient_id' => $patient->id,
                'user_id' => $patient->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMsg = 'Error al eliminar el paciente: ' . $e->getMessage();
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
