<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use App\Models\BloodType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    use RefreshDatabase;


    public function test_admin_can_view_patients_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $response = $this->actingAs($admin)
            ->get(route('admin.patients.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_patient()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $bloodType = BloodType::create(['name' => 'O+']);

        $response = $this->actingAs($admin)
            ->post(route('admin.patients.store'), [
                'name' => 'New Patient',
                'email' => 'patient@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'id_number' => '123456789',
                'blood_type_id' => $bloodType->id,
            ]);

        $response->assertRedirect(route('admin.patients.index'));
        $this->assertDatabaseHas('users', ['email' => 'patient@example.com']);
    }

    public function test_admin_can_update_patient()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $bloodType = BloodType::create(['name' => 'O+']);

        $patient = Patient::create([
            'user_id' => $user->id,
            'blood_type_id' => $bloodType->id,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.patients.update', $patient), [
                'name' => 'Updated Name',
                'email' => $user->email,
                'medical_history' => 'Updated history',
                'allergies' => 'Penicillin',
            ]);

        $response->assertRedirect(route('admin.patients.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_delete_patient()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $bloodType = BloodType::create(['name' => 'O+']);
        $patient = Patient::create([
            'user_id' => $user->id,
            'blood_type_id' => $bloodType->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.patients.destroy', $patient));

        $response->assertRedirect(route('admin.patients.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_non_admin_cannot_manage_patients()
    {
        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $response = $this->actingAs($user)
            ->get(route('admin.patients.index'));

        $response->assertStatus(403);
    }
}

