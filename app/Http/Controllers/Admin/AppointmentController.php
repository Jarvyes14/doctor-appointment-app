<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.appointments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.appointments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Administrado por Livewire
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Appointment $appointment)
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Administrado por Livewire
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index')->with('swal', [
            'icon' => 'success',
            'title' => '¡Eliminada!',
            'text' => 'La cita ha sido eliminada correctamente.'
        ]);
    }
}
