<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user');
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasRole('Doctor') || $doctor->user_id !== $user->id) {
            abort(403);
        }

        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasRole('Doctor') || $doctor->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'speciality_id' => 'required|exists:specialities,id',
            'medical_license_number' => 'required|string|max:50|unique:doctors,medical_license_number,' . $doctor->id,
            'biography' => 'nullable|string|max:1000',
        ]);

        $doctor->update($validated);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', 'Perfil actualizado exitosamente');
    }
}
