<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwilioWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Webhook para recibir las respuestas de WhatsApp de Twilio
Route::post('/twilio/webhook', [TwilioWebhookController::class, 'handleWebhook']);
