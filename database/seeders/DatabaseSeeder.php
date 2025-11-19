<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar a RoleSeeder
        $this->call([
           RoleSeeder::class,
            UserSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Javier Barceló',
            'email' => 'javierbarcelosantos@example.com',
            'password' => Hash::make('12345678'),
            'id_number' => '123456789', // NUEVO
            'phone' => '+1234567890',   // NUEVO
            'address' => 'Dirección de prueba', // NUEVO
        ]);
    }
}
