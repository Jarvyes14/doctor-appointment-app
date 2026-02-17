<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunos pacientes de prueba
        // Los usuarios ya existen, ahora solo crear sus registros de paciente
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Paciente');
        })->get();

        foreach ($users as $user) {
            Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type_id' => rand(1, 8),
                    'medical_history' => 'Sin historial médico registrado',
                    'allergies' => 'No reportadas',
                ]
            );
        }
    }
}

