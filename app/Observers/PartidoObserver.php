<?php

namespace App\Observers;

use App\Models\Partido;
use App\Models\Clasificacion;

class PartidoObserver
{
    
    public function created(Partido $partido): void
    {
        
        $this->ensureClasificacionesExist($partido);
        
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->actualizarClasificaciones($partido);
        }
    }

    public function updated(Partido $partido): void
    {
        
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->actualizarClasificaciones($partido);
        }
    }

    public function deleted(Partido $partido): void
    {
        
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->revertirClasificaciones($partido);
        }
    }

    public function restored(Partido $partido): void
    {
        
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->actualizarClasificaciones($partido);
        }
    }

    public function forceDeleted(Partido $partido): void
    {
        
    }

    private function ensureClasificacionesExist(Partido $partido): void
    {
        
        Clasificacion::firstOrCreate([
            'idEquipo' => $partido->idEquipoLocal,
            'idTorneo' => $partido->idTorneo,
        ], [
            'puntos' => 0,
            'partidosJugados' => 0,
            'victorias' => 0,
            'empates' => 0,
            'derrotas' => 0,
            'golesFavor' => 0,
            'golesContra' => 0,
        ]);

        Clasificacion::firstOrCreate([
            'idEquipo' => $partido->idEquipoVisitante,
            'idTorneo' => $partido->idTorneo,
        ], [
            'puntos' => 0,
            'partidosJugados' => 0,
            'victorias' => 0,
            'empates' => 0,
            'derrotas' => 0,
            'golesFavor' => 0,
            'golesContra' => 0,
        ]);
    }

    private function actualizarClasificaciones(Partido $partido): void
    {
        
        $original = $partido->getOriginal();
        $teníaResultados = isset($original['resultadoLocal']) && $original['resultadoLocal'] !== null 
                           && isset($original['resultadoVisitante']) && $original['resultadoVisitante'] !== null;
        
        $resultadosCambiaron = false;
        if ($teníaResultados) {
            $resultadosCambiaron = $original['resultadoLocal'] != $partido->resultadoLocal 
                                || $original['resultadoVisitante'] != $partido->resultadoVisitante;
        }

        if ($teníaResultados && $resultadosCambiaron) {
            
            $this->revertirClasificacionesConResultados(
                $partido,
                $original['resultadoLocal'],
                $original['resultadoVisitante']
            );
        }

        $this->ensureClasificacionesExist($partido);

        $clasificacionLocal = Clasificacion::where('idEquipo', $partido->idEquipoLocal)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        $clasificacionVisitante = Clasificacion::where('idEquipo', $partido->idEquipoVisitante)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        $golesLocal = $partido->resultadoLocal;
        $golesVisitante = $partido->resultadoVisitante;

        $clasificacionLocal->partidosJugados++;
        $clasificacionLocal->golesFavor += $golesLocal;
        $clasificacionLocal->golesContra += $golesVisitante;

        $clasificacionVisitante->partidosJugados++;
        $clasificacionVisitante->golesFavor += $golesVisitante;
        $clasificacionVisitante->golesContra += $golesLocal;

        if ($golesLocal > $golesVisitante) {
            
            $clasificacionLocal->victorias++;
            $clasificacionLocal->puntos += 3;
            $clasificacionVisitante->derrotas++;
        } elseif ($golesLocal < $golesVisitante) {
            
            $clasificacionVisitante->victorias++;
            $clasificacionVisitante->puntos += 3;
            $clasificacionLocal->derrotas++;
        } else {
            
            $clasificacionLocal->empates++;
            $clasificacionLocal->puntos += 1;
            $clasificacionVisitante->empates++;
            $clasificacionVisitante->puntos += 1;
        }

        $clasificacionLocal->save();
        $clasificacionVisitante->save();
    }

    private function revertirClasificaciones(Partido $partido): void
    {
        $this->revertirClasificacionesConResultados(
            $partido,
            $partido->resultadoLocal,
            $partido->resultadoVisitante
        );
    }

    private function revertirClasificacionesConResultados(Partido $partido, $golesLocal, $golesVisitante): void
    {
        
        $clasificacionLocal = Clasificacion::where('idEquipo', $partido->idEquipoLocal)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        $clasificacionVisitante = Clasificacion::where('idEquipo', $partido->idEquipoVisitante)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        if (!$clasificacionLocal || !$clasificacionVisitante) {
            return;
        }

        $clasificacionLocal->partidosJugados = max(0, $clasificacionLocal->partidosJugados - 1);
        $clasificacionLocal->golesFavor = max(0, $clasificacionLocal->golesFavor - $golesLocal);
        $clasificacionLocal->golesContra = max(0, $clasificacionLocal->golesContra - $golesVisitante);

        $clasificacionVisitante->partidosJugados = max(0, $clasificacionVisitante->partidosJugados - 1);
        $clasificacionVisitante->golesFavor = max(0, $clasificacionVisitante->golesFavor - $golesVisitante);
        $clasificacionVisitante->golesContra = max(0, $clasificacionVisitante->golesContra - $golesLocal);

        if ($golesLocal > $golesVisitante) {
            
            $clasificacionLocal->victorias = max(0, $clasificacionLocal->victorias - 1);
            $clasificacionLocal->puntos = max(0, $clasificacionLocal->puntos - 3);
            $clasificacionVisitante->derrotas = max(0, $clasificacionVisitante->derrotas - 1);
        } elseif ($golesLocal < $golesVisitante) {
            
            $clasificacionVisitante->victorias = max(0, $clasificacionVisitante->victorias - 1);
            $clasificacionVisitante->puntos = max(0, $clasificacionVisitante->puntos - 3);
            $clasificacionLocal->derrotas = max(0, $clasificacionLocal->derrotas - 1);
        } else {
            
            $clasificacionLocal->empates = max(0, $clasificacionLocal->empates - 1);
            $clasificacionLocal->puntos = max(0, $clasificacionLocal->puntos - 1);
            $clasificacionVisitante->empates = max(0, $clasificacionVisitante->empates - 1);
            $clasificacionVisitante->puntos = max(0, $clasificacionVisitante->puntos - 1);
        }

        $clasificacionLocal->save();
        $clasificacionVisitante->save();
    }
}
