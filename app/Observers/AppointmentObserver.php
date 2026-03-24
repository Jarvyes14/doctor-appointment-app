<?php

namespace App\Observers;

use App\Models\Appointment;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        // Cargar las relaciones necesarias para obtener los datos
        $appointment->load(['patient', 'doctor.user']);

        $patientUser = $appointment->patient;
        $doctorUser = $appointment->doctor->user;

        // --- ENVIAR CORREOS CON PDF ---
        try {
            \Illuminate\Support\Facades\Mail::to($patientUser->email)->send(new \App\Mail\AppointmentCreatedMail($appointment, $patientUser->name));
            \Illuminate\Support\Facades\Mail::to($doctorUser->email)->send(new \App\Mail\AppointmentCreatedMail($appointment, $doctorUser->name));
        } catch (\Exception $e) {
            Log::error('Error sending Appointment emails: ' . $e->getMessage());
        }

        $phone = $patientUser->phone;

        if (!$phone) {
            return;
        }

        // Limpiamos cualquier carácter que no sea número
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        // Si el teléfono tiene 10 dígitos (típico en México), le agregamos el +521
        if (strlen($cleanPhone) === 10) {
            $formattedPhone = '+521' . $cleanPhone;
        } else {
            // Si tiene otra longitud, simplemente le ponemos el + al principio
            $formattedPhone = '+' . $cleanPhone;
        }

        $whatsappTo = 'whatsapp:' . $formattedPhone;

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            Log::warning('Twilio credentials not configured.');
            return;
        }

        try {
            // Omitimos la verificación SSL en entorno local de Windows
            $httpClient = new \Twilio\Http\CurlClient([
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);

            $client = new Client($sid, $token, null, null, $httpClient);
            
            // Forzar el estado a pending desde la creación
            $appointment->status = 'pending';
            // Guardar sin disparar eventos en loop
            $appointment->saveQuietly();

            $dateFormatted = \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
            $timeFormatted = \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i');

            $message = "Hola {$patientUser->name}, tu cita médica con el Dr/a. {$doctorUser->name} ha sido registrada para el {$dateFormatted} a las {$timeFormatted}. \n\n*Por favor, responde a este mismo mensaje con la palabra Confirmar para que tu cita sea aprobada.*";

            $client->messages->create(
                $whatsappTo,
                [
                    'from' => $from,
                    'body' => $message
                ]
            );
            
            Log::info("WhatsApp message sent to {$whatsappTo} for Appointment ID: {$appointment->id}");

        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp Sending Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}

