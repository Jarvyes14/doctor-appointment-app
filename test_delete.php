<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST DE ELIMINACIÓN ===\n\n";

// Contar usuarios
$totalUsers = \App\Models\User::count();
echo "Total usuarios en BD: {$totalUsers}\n";

// Listar usuarios
$users = \App\Models\User::all();
echo "\nUsuarios:\n";
foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->implode(', ');
    echo "  - ID: {$user->id}, Email: {$user->email}, Roles: {$roles}\n";
}

// Verificar pacientes
$totalPatients = \App\Models\Patient::count();
echo "\nTotal pacientes: {$totalPatients}\n";

echo "\n¿Deseas eliminar al usuario ID 4? (s/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) != 's') {
    echo "Cancelado.\n";
    exit;
}

// Intentar eliminar usuario ID 4
try {
    $usuario = \App\Models\User::find(4);

    if (!$usuario) {
        echo "Usuario 4 no encontrado.\n";
        exit;
    }

    echo "\nEliminando usuario: {$usuario->email}\n";

    \DB::beginTransaction();

    // Eliminar citas
    $appointmentsCount = $usuario->appointments()->count();
    echo "  - Citas a eliminar: {$appointmentsCount}\n";
    $usuario->appointments()->delete();

    // Eliminar paciente si existe
    if ($usuario->patient) {
        echo "  - Eliminando registro de paciente...\n";
        $usuario->patient->delete();
    }

    // Eliminar doctor si existe
    if ($usuario->doctor) {
        echo "  - Eliminando registro de doctor...\n";
        $usuario->doctor->delete();
    }

    // Eliminar roles
    echo "  - Desasociando roles...\n";
    $usuario->roles()->detach();

    // Eliminar usuario
    echo "  - Eliminando usuario...\n";
    $resultado = $usuario->delete();

    if (!$resultado) {
        throw new \Exception('No se pudo eliminar el usuario del modelo');
    }

    \DB::commit();

    echo "\n✅ Usuario eliminado exitosamente!\n";

    // Verificar
    $totalUsersAfter = \App\Models\User::count();
    echo "\nTotal usuarios después: {$totalUsersAfter}\n";
    echo "Diferencia: " . ($totalUsers - $totalUsersAfter) . "\n";

} catch (\Exception $e) {
    \DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}

