<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorManagementTest extends TestCase
{
    use RefreshDatabase;


    public function test_doctor_can_edit_own_profile()
    {
        $doctorUser = User::factory()->create();
        $doctorUser->assignRole('Doctor');

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'license_number' => 'LIC-001',
            'specialty' => 'Cardiology',
        ]);

        $response = $this->actingAs($doctorUser)
            ->patch(route('doctors.update', $doctor), [
                'specialty' => 'Dermatology',
                'license_number' => 'LIC-002',
                'phone' => '555-1234',
                'address' => 'New Address',
            ]);

        $response->assertRedirect(route('doctors.show', $doctor));
        $this->assertDatabaseHas('doctors', [
            'id' => $doctor->id,
            'specialty' => 'Dermatology',
        ]);
    }

    public function test_doctor_cannot_edit_other_doctor_profile()
    {
        $doctor1User = User::factory()->create();
        $doctor1User->assignRole('Doctor');

        $doctor2User = User::factory()->create();
        $doctor2User->assignRole('Doctor');

        $doctor1 = Doctor::create([
            'user_id' => $doctor1User->id,
            'license_number' => 'LIC-001',
        ]);

        $doctor2 = Doctor::create([
            'user_id' => $doctor2User->id,
            'license_number' => 'LIC-002',
        ]);

        $response = $this->actingAs($doctor1User)
            ->patch(route('doctors.update', $doctor2), [
                'specialty' => 'Cardiology',
                'license_number' => 'LIC-002',
            ]);

        $response->assertStatus(403);
    }
}

