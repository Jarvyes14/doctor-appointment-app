<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;

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
            'medical_license_number' => 'nullable|string|max:255',
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
            'medical_license_number' => 'nullable|string|max:255',
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
        $user = $doctor->user;
        $doctor->delete();

        if ($user) {
            $user->delete();
        }

        return response()->json(['message' => 'Doctor y usuario eliminados correctamente.']);
    }
}
