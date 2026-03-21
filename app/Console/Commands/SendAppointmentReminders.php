<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Twilio\Rest\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un recordatorio de cita por WhatsApp exactamente un día antes de la cita';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando envío de recordatorios de citas...');

        // Buscamos las citas que sean exactamente mañana y estén confirmadas o pendientes (agendadas).
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('date', $tomorrow)
            ->whereIn('status', ['confirmado', 'confirmed', 'pending', 'agendado'])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas programadas para mañana.');
            return;
        }

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            $this->error('Credenciales de Twilio no configuradas en el .env');
            return;
        }

        // Omitimos validación SSL en entorno local (como el Observer)
        $httpClient = new \Twilio\Http\CurlClient([
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $twilioClient = new Client($sid, $token, null, null, $httpClient);

        $sentCount = 0;

        foreach ($appointments as $appointment) {
            try {
                $patientUser = $appointment->patient->user ?? null;
                $doctorUser = $appointment->doctor->user ?? null;
                
                if (!$patientUser || !$doctorUser) continue;

                $phone = $patientUser->phone;

                if (!$phone) {
                    continue;
                }

                // Formateamos el teléfono igual que en el Observer
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                
                if (strlen($cleanPhone) === 10) {
                    $formattedPhone = '+521' . $cleanPhone;
                } else {
                    $formattedPhone = '+' . $cleanPhone;
                }

                $whatsappTo = 'whatsapp:' . $formattedPhone;

                $timeFormatted = Carbon::parse($appointment->start_time)->format('H:i');

                // Mensaje del recordatorio
                $message = "Hola {$patientUser->name} 👋, este es un recordatorio automático: tienes una cita médica mañana con el Dr/a. {$doctorUser->name} a las {$timeFormatted} hrs. ¡Te esperamos!";

                $twilioClient->messages->create(
                    $whatsappTo,
                    [
                        'from' => $from,
                        'body' => $message
                    ]
                );

                Log::info("Recordatorio enviado a {$whatsappTo} para la Cita ID: {$appointment->id}");
                $sentCount++;

                $this->info("✔ Mensaje enviado a {$patientUser->name} ({$whatsappTo})");

            } catch (\Exception $e) {
                Log::error("Error enviando recordatorio Cita ID {$appointment->id}: " . $e->getMessage());
                $this->error("❌ Fallo al enviar a la Cita ID {$appointment->id} - Revisa los logs.");
            }
        }

        $this->info("Proceso terminado. Total de recordatorios enviados: {$sentCount}");
    }
}
