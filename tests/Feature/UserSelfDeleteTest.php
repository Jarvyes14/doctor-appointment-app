<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

class UserSelfDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_self_user(): void
    {

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'Paciente', 'guard_name' => 'web']);

        $this->seed(UserSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user, 'web');

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
