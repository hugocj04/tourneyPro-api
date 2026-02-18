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
                'asistencias' => 0,
                'tarjetasAmarillas' => 0,
                'tarjetasRojas' => 0,
                'minutosJugados' => 0,
                'partidosJugados' => 0,
            ]
        );

        // Actualizar según el tipo de evento
        switch ($eventoPartido->tipoEvento) {
            case 'gol':
            case 'autogol':
                $estadistica->goles++;
                break;
            case 'tarjeta_amarilla':
                $estadistica->tarjetasAmarillas++;
                break;
            case 'tarjeta_roja':
                $estadistica->tarjetasRojas++;
                break;
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
        switch ($tipoEvento) {
            case 'gol':
            case 'autogol':
                $estadistica->goles = max(0, $estadistica->goles - 1);
                break;
            case 'tarjeta_amarilla':
                $estadistica->tarjetasAmarillas = max(0, $estadistica->tarjetasAmarillas - 1);
                break;
            case 'tarjeta_roja':
                $estadistica->tarjetasRojas = max(0, $estadistica->tarjetasRojas - 1);
                break;
        }

        $estadistica->save();
    }
}
