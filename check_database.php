<?php

// Script para verificar la BD directamente
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\User;

// Inicializar la aplicación
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$kernel->handle($request);

// Contar usuarios
echo "Total de usuarios: " . User::count() . "\n";
echo "\nListado de usuarios:\n";
echo "ID | Nombre | Email | Roles\n";
echo "---|--------|-------|------\n";

foreach (User::with('roles')->get() as $user) {
    $roles = $user->roles->pluck('name')->implode(', ') ?: 'Sin rol';
    echo $user->id . " | " . $user->name . " | " . $user->email . " | " . $roles . "\n";
}

echo "\nTotal de citas: " . \App\Models\Appointment::count() . "\n";
echo "Total de pacientes: " . \App\Models\Patient::count() . "\n";
echo "Total de doctores: " . \App\Models\Doctor::count() . "\n";

