<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el usuario doctor creado en UserSeeder
        $doctorUser = User::where('email', 'doctor@example.com')->first();

        if ($doctorUser) {
            Doctor::create([
                'user_id' => $doctorUser->id,
                'specialty' => 'Medicina General',
                'license_number' => 'LIC-001-2024',
                'phone' => '555-0002',
                'address' => 'Avenida Médica 456',
            ]);
        }
    }
}

