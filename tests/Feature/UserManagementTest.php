<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $response = $this->actingAs($admin)
            ->get(route('admin.usuarios.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $response = $this->actingAs($admin)
            ->post(route('admin.usuarios.store'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Paciente',
            ]);

        $response->assertRedirect(route('admin.usuarios.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_non_admin_cannot_manage_users()
    {
        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $response = $this->actingAs($user)
            ->get(route('admin.usuarios.index'));

        $response->assertStatus(403);
    }
}

