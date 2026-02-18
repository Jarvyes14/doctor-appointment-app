<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;


    public function test_patient_can_create_appointment()
    {
        $patient = User::factory()->create();
        $patient->assignRole('Paciente');

        $doctor = User::factory()->create();
        $doctor->assignRole('Doctor');
        $doctorModel = Doctor::create([
            'user_id' => $doctor->id,
            'license_number' => 'LIC-001',
        ]);

        $response = $this->actingAs($patient)
            ->post(route('appointments.store'), [
                'doctor_id' => $doctorModel->id,
                'appointment_date' => now()->addDays(1)->format('Y-m-d H:i'),
                'reason' => 'Medical checkup',
                'notes' => 'No notes',
            ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctorModel->id,
            'reason' => 'Medical checkup',
        ]);
    }

    public function test_patient_cannot_view_other_patients_appointments()
    {
        $patient1 = User::factory()->create();
        $patient1->assignRole('Paciente');

        $patient2 = User::factory()->create();
        $patient2->assignRole('Paciente');

        $doctor = User::factory()->create();
        $doctor->assignRole('Doctor');
        $doctorModel = Doctor::create([
            'user_id' => $doctor->id,
            'license_number' => 'LIC-001',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctorModel->id,
            'appointment_date' => now()->addDays(1),
            'reason' => 'Checkup',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($patient1)
            ->get(route('appointments.show', $appointment));

        $response->assertStatus(403);
    }
}

