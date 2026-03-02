<?php

namespace App\Observers;

use App\Models\EventoPartido;
use App\Models\EstadisticaJugador;
use Illuminate\Support\Facades\Log;

class EventoPartidoObserver
{
    /**
     * Handle the EventoPartido "created" event.
     */
    public function created(EventoPartido $eventoPartido): void
    {
        Log::info('EventoPartidoObserver::created disparado', [
            'idEvento' => $eventoPartido->idEvento,
            'idJugador' => $eventoPartido->idJugador,
            'tipoEvento' => $eventoPartido->tipoEvento,
        ]);
        
        // Solo actualizar si hay un jugador asociado
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
    }

    /**
     * Handle the EventoPartido "updated" event.
     */
    public function updated(EventoPartido $eventoPartido): void
    {
        // Revertir estadísticas del tipo de evento anterior
        $original = $eventoPartido->getOriginal();
        if ($original['idJugador']) {
            $this->revertirEstadisticas($eventoPartido, $original['tipoEvento']);
        }

        // Aplicar estadísticas del nuevo tipo de evento
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
    }

    /**
     * Handle the EventoPartido "deleted" event.
     */
    public function deleted(EventoPartido $eventoPartido): void
    {
        if ($eventoPartido->idJugador) {
            $this->revertirEstadisticas($eventoPartido, $eventoPartido->tipoEvento);
        }
    }

    /**
     * Handle the EventoPartido "restored" event.
     */
    public function restored(EventoPartido $eventoPartido): void
    {
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
    }

    /**
     * Handle the EventoPartido "force deleted" event.
     */
    public function forceDeleted(EventoPartido $eventoPartido): void
    {
        //
    }

    /**
     * Actualizar estadísticas del jugador según el tipo de evento
     */
    private function actualizarEstadisticas(EventoPartido $eventoPartido): void
    {
        // Cargar relación si no está cargada
        if (!$eventoPartido->relationLoaded('partido')) {
            $eventoPartido->load('partido');
        }
        
        $idTorneo = $eventoPartido->partido->idTorneo;

        // Obtener o crear estadística del jugador para este torneo
        $estadistica = EstadisticaJugador::firstOrCreate(
            [
                'idJugador' => $eventoPartido->idJugador,
                'idTorneo' => $idTorneo,
            ],
            [
                'goles' => 0,
                'partidosJugados' => 0,
            ]
        );

        if ($eventoPartido->tipoEvento === 'gol') {
            $estadistica->goles++;
        }

        $estadistica->save();
    }

    /**
     * Revertir estadísticas del jugador según el tipo de evento
     */
    private function revertirEstadisticas(EventoPartido $eventoPartido, string $tipoEvento): void
    {
        // Cargar relación si no está cargada
        if (!$eventoPartido->relationLoaded('partido')) {
            $eventoPartido->load('partido');
        }
        
        $idTorneo = $eventoPartido->partido->idTorneo;

        $estadistica = EstadisticaJugador::where('idJugador', $eventoPartido->idJugador)
            ->where('idTorneo', $idTorneo)
            ->first();

        if (!$estadistica) {
            return;
        }

        // Revertir según el tipo de evento
        if ($tipoEvento === 'gol') {
            $estadistica->goles = max(0, $estadistica->goles - 1);
        }

        $estadistica->save();
    }
}
