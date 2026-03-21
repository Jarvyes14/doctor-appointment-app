<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Models\Appointment;
use App\Observers\AppointmentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registro del Observer para las citas (Mensajes de Twilio)
        Appointment::observe(AppointmentObserver::class);

        // 1. Desactiva observers mientras se ejecute el seeder
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            Model::unsetEventDispatcher();
        }

        // 2. Limpia el cache de Spatie 
        // AGREGAMOS ESTE IF: Solo limpia si NO estamos en la consola (o si prefieres, si no es una instalación limpia)
        if (!$this->app->runningInConsole()) {
            app()['cache']->forget('spatie.permission.cache');
        }
    }
}
