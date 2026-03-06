<?php

namespace App\Observers;

use App\Models\EventoPartido;
use App\Models\EstadisticaJugador;
use Illuminate\Support\Facades\Log;

class EventoPartidoObserver
{
    
    public function created(EventoPartido $eventoPartido): void
    {
        Log::info('EventoPartidoObserver::created disparado', [
            'idEvento' => $eventoPartido->idEvento,
            'idJugador' => $eventoPartido->idJugador,
            'tipoEvento' => $eventoPartido->tipoEvento,
        ]);
        
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
    }

    public function updated(EventoPartido $eventoPartido): void
    {
        
        $original = $eventoPartido->getOriginal();
        if ($original['idJugador']) {
            $this->revertirEstadisticas($eventoPartido, $original['tipoEvento']);
        }

        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
    }

    public function deleted(EventoPartido $eventoPartido): void
    {
        if ($eventoPartido->idJugador) {
            $this->revertirEstadisticas($eventoPartido, $eventoPartido->tipoEvento);
        }
    }

    public function restored(EventoPartido $eventoPartido): void
    {
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
    }

    public function forceDeleted(EventoPartido $eventoPartido): void
    {
        
    }

    private function actualizarEstadisticas(EventoPartido $eventoPartido): void
    {
        
        if (!$eventoPartido->relationLoaded('partido')) {
            $eventoPartido->load('partido');
        }
        
        $idTorneo = $eventoPartido->partido->idTorneo;

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

    private function revertirEstadisticas(EventoPartido $eventoPartido, string $tipoEvento): void
    {
        
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

        if ($tipoEvento === 'gol') {
            $estadistica->goles = max(0, $estadistica->goles - 1);
        }

        $estadistica->save();
    }
}
