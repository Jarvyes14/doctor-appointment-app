<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir usuarios
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'id_number' => '12345678',
                'phone' => '555-0001',
                'address' => 'Calle Principal 123',
                'role' => 'Administrador'
            ],
            [
                'name' => 'Dr. Juan Pérez',
                'email' => 'doctor@example.com',
                'password' => Hash::make('doctor'),
                'id_number' => '87654321',
                'phone' => '555-0002',
                'address' => 'Avenida Médica 456',
                'role' => 'Doctor'
            ]
        ];

        // Crear usuarios en la DB
        foreach($users as $userData){
            $role = $userData['role'];
            unset($userData['role']); // Remover 'role' del array antes de crear

            $user = User::create($userData);
            $user->assignRole($role); // Asignar el rol correspondiente
        }
    }
}
