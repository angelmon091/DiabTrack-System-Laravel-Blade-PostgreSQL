<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Clase AppServiceProvider
 * 
 * Proveedor de servicios de la aplicación.
 * Se ejecuta durante el registro y arranque de la aplicación.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de la aplicación.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranque de cualquier servicio de la aplicación.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
