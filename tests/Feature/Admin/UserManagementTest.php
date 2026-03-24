<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_delete_an_administrator()
    {
        $targetAdmin = User::factory()->create();
        $targetAdmin->assignRole('Administrador');

        $attacker = User::factory()->create();
        $attacker->assignRole('Paciente');

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        $response = $this->actingAs($attacker)
            ->delete(route('admin.users.destroy', $targetAdmin));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $targetAdmin->id]);
    }

    public function test_cannot_delete_role_assigned_to_users()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        // Create enough roles to pass the id <= 4 check
        Role::create(['name' => 'Filler1']);
        Role::create(['name' => 'Filler2']);
        
        $roleDummy = Role::create(['name' => 'DummyRole']);
        $doctor = User::factory()->create();
        $doctor->assignRole($roleDummy);

        $response = $this->actingAs($admin)
            ->delete(route('admin.roles.destroy', $roleDummy->id));

        $response->assertSessionHas('swal', function ($swal) {
            return $swal['icon'] === 'warning' && str_contains($swal['text'], 'tiene usuarios asociados');
        });
        $this->assertDatabaseHas('roles', ['id' => $roleDummy->id]);
    }

    public function test_admin_cannot_remove_their_own_admin_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'name' => 'Admin Test',
                'email' => $admin->email,
                'role' => 'Paciente',
            ]);

        $response->assertSessionHas('swal', function ($swal) {
            return $swal['icon'] === 'warning' && str_contains($swal['text'], 'No puedes quitarte el rol');
        });

        $this->assertTrue($admin->fresh()->hasRole('Administrador'));
    }
}
