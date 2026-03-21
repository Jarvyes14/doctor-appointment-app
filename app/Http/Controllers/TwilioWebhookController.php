<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Twilio envía variables como From, To, Body
        $from = $request->input('From'); // Ejemplo: whatsapp:+5219999554504
        $body = trim(strtolower($request->input('Body')));

        Log::info("Mensaje de Twilio recibido desde: {$from} - Contenido: {$body}");

        // Verificamos si el mensaje es "confirmar" o similar
        if (str_contains($body, 'confirmar')) {
            // Extraer solo los números del remitente para buscar al usuario
            $cleanPhone = preg_replace('/[^0-9]/', '', $from);
            
            // Vamos a buscar los últimos 10 dígitos (que normalmente el usuario registra en la app)
            $last10Digits = substr($cleanPhone, -10);

            // Buscamos a un usuario que tenga en su teléfono estos últimos 10 dígitos
            $user = User::where('phone', 'like', '%' . $last10Digits . '%')->first();

            if ($user && $user->patient) {
                Log::info("Usuario encontrado: {$user->name}");

                // Buscamos la cita más reciente de este paciente que esté 'pending' (o agendado)
                $appointment = Appointment::where('patient_id', $user->patient->id)
                    ->whereIn('status', ['pending', 'agendado'])
                    ->latest()
                    ->first();

                if ($appointment) {
                    // Cambiamos el estado a confirmado
                    $appointment->status = 'confirmado'; // O 'confirmed' dependiendo de cómo lo muestres en Livewire
                    $appointment->save();

                    Log::info("Cita {$appointment->id} confirmada para el usuario {$user->name}");

                    // Opcional: Responderle al usuario que su cita fue confirmada
                    $this->replyWhatsApp($from, "✅ Tu cita ha sido agendada de manera exitosa.");
                    return response('OK', 200);
                } else {
                    $this->replyWhatsApp($from, "No encontramos ninguna cita pendiente por confirmar para ti en este momento.");
                    return response('OK', 200);
                }
            } else {
                Log::warning("No se encontró ningún paciente con el número que contenga: {$last10Digits}");
            }
        }

        return response('OK', 200);
    }

    private function replyWhatsApp($to, $messageBody)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            return;
        }

        try {
            // Omitimos la verificación SSL en entorno local de Windows
            $httpClient = new \Twilio\Http\CurlClient([
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);

            $client = new Client($sid, $token, null, null, $httpClient);

            $client->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $messageBody
                ]
            );
        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp Response Error: ' . $e->getMessage());
        }
    }
}

