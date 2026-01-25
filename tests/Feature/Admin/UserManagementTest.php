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
        // 1. Preparación
        $targetAdmin = User::factory()->create();
        $targetAdmin->assignRole('Administrador');

        $attacker = User::factory()->create();
        $attacker->assignRole('Paciente');

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 2. Acción
        $response = $this->actingAs($attacker)
            ->delete(route('admin.users.destroy', $targetAdmin));

        // 3. Verificación de redirección
        $response->assertStatus(302);

        // 4. Verificación de Datos en Sesión (Paso a paso)
        $swal = session('swal');

        $this->assertNotNull($swal, 'La sesión no contiene la clave [swal].');
        $this->assertEquals('error', $swal['icon'], 'El icono de la alerta no es [error].');
        $this->assertEquals('No tienes permisos para eliminar a un administrador.', $swal['text'], 'El texto de la alerta no coincide.');

        // 5. Verificación de Base de Datos
        $this->assertDatabaseHas('users', ['id' => $targetAdmin->id]);
    }

    public function test_cannot_delete_role_assigned_to_users()
    {
        // Arrange: Un admin y un rol de 'Doctor' con un usuario asignado
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        $roleDoctor = Role::where('name', 'Doctor')->first();
        $doctor = User::factory()->create();
        $doctor->assignRole($roleDoctor);

        // Act: Intentar borrar el rol (usando el ID del rol, ya que tu controlador recibe string $id)
        $response = $this->actingAs($admin)
            ->delete(route('admin.roles.destroy', $roleDoctor->id));

        // Assert: Verificar el swal de 'Rol en uso'
        $response->assertSessionHas('swal', function ($swal) {
            return $swal['icon'] === 'warning' && str_contains($swal['text'], 'tiene usuarios asociados');
        });
        $this->assertDatabaseHas('roles', ['id' => $roleDoctor->id]);
    }

    public function test_admin_cannot_remove_their_own_admin_role()
    {
        // Arrange: Admin logeado
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');

        // Act: Intenta cambiarse el rol a Paciente
        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'name' => 'Admin Test',
                'email' => $admin->email,
                'role' => 'Paciente', // Cambio a rol inferior
            ]);

        // Assert: El sistema debe bloquearlo
        $response->assertSessionHas('swal', function ($swal) {
            return $swal['icon'] === 'warning' && str_contains($swal['text'], 'No puedes quitarte el rol');
        });

        // Refrescamos el modelo desde la DB para verificar que sigue siendo Admin
        $this->assertTrue($admin->fresh()->hasRole('Administrador'));
    }
}
