<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyAdminReportMail;
use App\Mail\DailyDoctorReportMail;
use Illuminate\Support\Facades\Log;

class SendDailyReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía reportes diarios de citas al administrador y a los doctores correspondientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        
        // Citas de hoy en general (con relaciones)
        $appointmentsToday = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_date')
            ->get();

        if ($appointmentsToday->isEmpty()) {
            $this->info('No hay citas para el día de hoy.');
            return;
        }

        // 1. Obtener al administrador (asumiendo rol 'Admin') o a un correo específico
        $admins = User::role('Admin')->get();
        if ($admins->isEmpty()) {
            Log::warning('No se encontraron usuarios con rol Admin para enviar el reporte diario.');
            $this->warn('No se encontraron administradores.');
            // Puedes colocar un fallback aquí, por ejemplo un correo fijo:
            // Mail::to('admin@tudominio.com')->send(new DailyAdminReportMail($appointmentsToday));
        } else {
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new DailyAdminReportMail($appointmentsToday));
                $this->info("Reporte enviado al administrador: {$admin->email}");
            }
        }

        // 2. Agrupar las citas por doctor y enviarlas
        $appointmentsByDoctor = $appointmentsToday->groupBy('doctor_id');

        foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
            $doctor = Doctor::with('user')->find($doctorId);
            if ($doctor && $doctor->user) {
                Mail::to($doctor->user->email)->send(new DailyDoctorReportMail($doctorAppointments, $doctor->user->name));
                $this->info("Reporte enviado al doctor: {$doctor->user->email}");
            }
        }

        $this->info('Reportes diarios enviados correctamente.');
    }
}