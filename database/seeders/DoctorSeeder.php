<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Doctor Juan Pérez (Cardiología)
        $userJuan = User::firstOrCreate(
            ['email' => 'juan.perez@simify.com'],
            [
                'name' => 'Dr. Juan Pérez',
                'password' => Hash::make('password'),
                'phone' => '555-0101',
                'id_number' => 'DOC-001'
            ]
        );
        $userJuan->syncRoles('Doctor');

        $cardio = Speciality::where('name', 'Cardiología')->first() ?? Speciality::create(['name' => 'Cardiología']);

        Doctor::firstOrCreate(
            ['user_id' => $userJuan->id],
            [
                'speciality_id' => $cardio->id,
                'biography' => 'Especialista en cardiología con 10 años de experiencia.'
            ]
        );

        // 2. Dra. Ana Gómez (Pediatría)
        $userAna = User::firstOrCreate(
            ['email' => 'ana.gomez@simify.com'],
            [
                'name' => 'Dra. Ana Gómez',
                'password' => Hash::make('password'),
                'phone' => '555-0102',
                'id_number' => 'DOC-002'
            ]
        );
        $userAna->syncRoles('Doctor');

        $pedia = Speciality::where('name', 'Pediatría')->first() ?? Speciality::create(['name' => 'Pediatría']);

        Doctor::firstOrCreate(
            ['user_id' => $userAna->id],
            [
                'speciality_id' => $pedia->id,
                'medical_license_number' => 'MED-AG-002',
                'biography' => 'Pediatra certificada, apasionada por la salud infantil.'
            ]
        );
    }
}

