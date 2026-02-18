<?php

namespace App\Providers;

use App\Models\Partido;
use App\Models\EventoPartido;
use App\Observers\PartidoObserver;
use App\Observers\EventoPartidoObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Registrar Observer para actualizar clasificaciones automáticamente
        Partido::observe(PartidoObserver::class);
        
        // Registrar Observer para actualizar estadísticas de jugadores
        EventoPartido::observe(EventoPartidoObserver::class);
    }
}
