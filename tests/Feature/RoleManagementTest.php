<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_roles_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $response = $this->actingAs($admin)
            ->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.index');
    }

    public function test_admin_can_create_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $response = $this->actingAs($admin)
            ->post(route('admin.roles.store'), [
                'name' => 'CustomRole',
            ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', [
            'name' => 'CustomRole',
        ]);
    }

    public function test_admin_cannot_edit_protected_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $role = Role::findByName('Paciente');

        $response = $this->actingAs($admin)
            ->patch(route('admin.roles.update', $role), [
                'name' => 'NewPaciente',
            ]);

        $response->assertRedirect(route('admin.roles.index'));
    }

    public function test_admin_can_delete_custom_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        // Crear un rol personalizado (no protegido)
        $role = Role::create(['name' => 'CustomRole99', 'guard_name' => 'web']);

        $response = $this->actingAs($admin)
            ->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        // El rol debería ser eliminado
        $this->assertDatabaseMissing('roles', [
            'name' => 'CustomRole99',
        ]);
    }

    public function test_non_admin_cannot_manage_roles()
    {
        $user = User::factory()->create();
        $user->assignRole('Paciente');

        $response = $this->actingAs($user)
            ->get(route('admin.roles.index'));

        $response->assertStatus(403);
    }
}

