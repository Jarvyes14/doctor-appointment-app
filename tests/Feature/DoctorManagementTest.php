<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Speciality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorManagementTest extends TestCase
{
    use RefreshDatabase;


    public function test_doctor_can_edit_own_profile()
    {
        $doctorUser = User::factory()->create();
        $doctorUser->assignRole('Doctor');

        $speciality1 = Speciality::create(['name' => 'Cardiology']);
        $speciality2 = Speciality::create(['name' => 'Dermatology']);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'medical_license_number' => 'LIC-001',
            'speciality_id' => $speciality1->id,
        ]);

        $response = $this->actingAs($doctorUser)
            ->patch(route('doctors.update', $doctor), [
                'speciality_id' => $speciality2->id,
                'medical_license_number' => 'LIC-002',
            ]);

        $response->assertRedirect(route('doctors.show', $doctor));
        $this->assertDatabaseHas('doctors', [
            'id' => $doctor->id,
            'speciality_id' => $speciality2->id,
        ]);
    }

    public function test_doctor_cannot_edit_other_doctor_profile()
    {
        $doctor1User = User::factory()->create();
        $doctor1User->assignRole('Doctor');

        $doctor2User = User::factory()->create();
        $doctor2User->assignRole('Doctor');

        $speciality = Speciality::create(['name' => 'Cardiology']);

        $doctor1 = Doctor::create([
            'user_id' => $doctor1User->id,
            'medical_license_number' => 'LIC-001',
            'speciality_id' => $speciality->id,
        ]);

        $doctor2 = Doctor::create([
            'user_id' => $doctor2User->id,
            'medical_license_number' => 'LIC-002',
            'speciality_id' => $speciality->id,
        ]);

        $response = $this->actingAs($doctor1User)
            ->patch(route('doctors.update', $doctor2), [
                'speciality_id' => $speciality->id,
                'medical_license_number' => 'LIC-002',
            ]);

        $response->assertStatus(403);
    }
}
