<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        // 1. Desactiva observers mientras se ejecute el seeder
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            Model::unsetEventDispatcher();
        }

        // 2. Limpia el cache de Spatie
        app()['cache']->forget('spatie.permission.cache');
    }
}
