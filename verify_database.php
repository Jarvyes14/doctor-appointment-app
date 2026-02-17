<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;

echo "════════════════════════════════════════════\n";
echo "         ESTADO ACTUAL DE LA BD\n";
echo "════════════════════════════════════════════\n\n";

echo "📊 USUARIOS:\n";
echo "─────────────────────────────────────────\n";
$users = User::all();
echo "Total: " . $users->count() . " usuarios\n\n";
echo "ID | Nombre | Email\n";
echo "───|─────────|──────────────────────────\n";
foreach ($users as $user) {
    printf("%d | %-15s | %s\n", $user->id, substr($user->name, 0, 15), $user->email);
}

echo "\n\n👥 PACIENTES:\n";
echo "─────────────────────────────────────────\n";
$patients = Patient::all();
echo "Total: " . $patients->count() . " pacientes\n\n";
echo "ID | User ID | Blood Type ID\n";
echo "───|─────────|───────────────\n";
foreach ($patients as $patient) {
    printf("%d | %7d | %s\n", $patient->id, $patient->user_id, $patient->blood_type_id ?? 'NULL');
}

echo "\n\n📅 CITAS:\n";
echo "─────────────────────────────────────────\n";
$appointments = Appointment::all();
echo "Total: " . $appointments->count() . " citas\n";

echo "\n════════════════════════════════════════════\n";

