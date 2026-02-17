<?php

namespace Tests\Feature;

use App\Models\BloodType;
use App\Models\Patient;
use App\Models\User;
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
        $response->assertViewIs('admin.patients.index');
    }

    public function test_admin_can_create_patient()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        BloodType::create(['name' => 'O+']);

        $response = $this->actingAs($admin)
            ->post(route('admin.patients.store'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'id_number' => '123456789',
                'phone' => '555-1234',
                'address' => 'Main St 123',
                'blood_type_id' => 1,
                'medical_history' => 'No history',
                'allergies' => 'No allergies',
            ]);

        $response->assertRedirect(route('admin.patients.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_admin_can_update_patient()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $patient = Patient::create([
            'user_id' => $user->id,
            'blood_type_id' => 1,
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

        $patient = Patient::create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.patients.destroy', $patient));

        $response->assertRedirect(route('admin.patients.index'));
        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_non_admin_cannot_manage_patients()
    {
        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $response = $this->actingAs($user)
            ->get(route('admin.patients.index'));

        // El middleware admin debería retornar 403
        $response->assertStatus(403);
    }
}

