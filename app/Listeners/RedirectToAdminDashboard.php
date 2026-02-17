<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class RedirectToAdminDashboard
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // El login se ha completado exitosamente
        // La redirección será manejada por la ruta raíz
    }
}

