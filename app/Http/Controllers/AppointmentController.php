<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('Paciente')) {
            $appointments = Appointment::where('patient_id', $user->id)
                ->with('doctor.user')
                ->orderBy('appointment_date', 'desc')
                ->paginate(15);
        } else {
            $appointments = Appointment::with(['patient', 'doctor.user'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(15);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = Doctor::with('user')->get();
        return view('appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date_format:Y-m-d H:i|after:now',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        Appointment::create([
            'patient_id' => $user->id,
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Cita creada exitosamente');
    }

    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('Paciente') && $appointment->patient_id !== $user->id) {
            abort(403);
        }

        $appointment->load(['patient', 'doctor.user']);
        return view('appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Estado de la cita actualizado');
    }
}

