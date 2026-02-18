<?php

namespace App\Observers;

use App\Models\Partido;
use App\Models\Clasificacion;

class PartidoObserver
{
    /**
     * Handle the Partido "created" event.
     */
    public function created(Partido $partido): void
    {
        // Asegurar que existan las clasificaciones
        $this->ensureClasificacionesExist($partido);
        
        // Si el partido ya tiene resultados al crearse, actualizarlos
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->actualizarClasificaciones($partido);
        }
    }

    /**
     * Handle the Partido "updated" event.
     */
    public function updated(Partido $partido): void
    {
        // Solo actualizar si el partido tiene resultados y está finalizado
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->actualizarClasificaciones($partido);
        }
    }

    /**
     * Handle the Partido "deleted" event.
     */
    public function deleted(Partido $partido): void
    {
        // Si el partido tenía resultados, revertir las estadísticas
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->revertirClasificaciones($partido);
        }
    }

    /**
     * Handle the Partido "restored" event.
     */
    public function restored(Partido $partido): void
    {
        // Si se restaura un partido con resultados, volver a calcular
        if ($partido->resultadoLocal !== null && $partido->resultadoVisitante !== null) {
            $this->actualizarClasificaciones($partido);
        }
    }

    /**
     * Handle the Partido "force deleted" event.
     */
    public function forceDeleted(Partido $partido): void
    {
        //
    }

    /**
     * Asegurar que existan clasificaciones para ambos equipos
     */
    private function ensureClasificacionesExist(Partido $partido): void
    {
        // Clasificación equipo local
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

        // Clasificación equipo visitante
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

    /**
     * Actualizar clasificaciones basado en el resultado del partido
     */
    private function actualizarClasificaciones(Partido $partido): void
    {
        // Verificar si el partido ya fue procesado anteriormente con resultados DIFERENTES
        $original = $partido->getOriginal();
        $teníaResultados = isset($original['resultadoLocal']) && $original['resultadoLocal'] !== null 
                           && isset($original['resultadoVisitante']) && $original['resultadoVisitante'] !== null;
        
        $resultadosCambiaron = false;
        if ($teníaResultados) {
            $resultadosCambiaron = $original['resultadoLocal'] != $partido->resultadoLocal 
                                || $original['resultadoVisitante'] != $partido->resultadoVisitante;
        }

        if ($teníaResultados && $resultadosCambiaron) {
            // Primero revertir el resultado anterior
            $this->revertirClasificacionesConResultados(
                $partido,
                $original['resultadoLocal'],
                $original['resultadoVisitante']
            );
        }

        // Asegurar que existan las clasificaciones
        $this->ensureClasificacionesExist($partido);

        // Obtener clasificaciones
        $clasificacionLocal = Clasificacion::where('idEquipo', $partido->idEquipoLocal)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        $clasificacionVisitante = Clasificacion::where('idEquipo', $partido->idEquipoVisitante)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        // Determinar resultado
        $golesLocal = $partido->resultadoLocal;
        $golesVisitante = $partido->resultadoVisitante;

        // Actualizar estadísticas equipo local
        $clasificacionLocal->partidosJugados++;
        $clasificacionLocal->golesFavor += $golesLocal;
        $clasificacionLocal->golesContra += $golesVisitante;

        // Actualizar estadísticas equipo visitante
        $clasificacionVisitante->partidosJugados++;
        $clasificacionVisitante->golesFavor += $golesVisitante;
        $clasificacionVisitante->golesContra += $golesLocal;

        // Determinar victoria, empate o derrota
        if ($golesLocal > $golesVisitante) {
            // Victoria local
            $clasificacionLocal->victorias++;
            $clasificacionLocal->puntos += 3;
            $clasificacionVisitante->derrotas++;
        } elseif ($golesLocal < $golesVisitante) {
            // Victoria visitante
            $clasificacionVisitante->victorias++;
            $clasificacionVisitante->puntos += 3;
            $clasificacionLocal->derrotas++;
        } else {
            // Empate
            $clasificacionLocal->empates++;
            $clasificacionLocal->puntos += 1;
            $clasificacionVisitante->empates++;
            $clasificacionVisitante->puntos += 1;
        }

        $clasificacionLocal->save();
        $clasificacionVisitante->save();
    }

    /**
     * Revertir clasificaciones cuando se elimina un partido
     */
    private function revertirClasificaciones(Partido $partido): void
    {
        $this->revertirClasificacionesConResultados(
            $partido,
            $partido->resultadoLocal,
            $partido->resultadoVisitante
        );
    }

    /**
     * Revertir clasificaciones con resultados específicos
     */
    private function revertirClasificacionesConResultados(Partido $partido, $golesLocal, $golesVisitante): void
    {
        // Obtener clasificaciones
        $clasificacionLocal = Clasificacion::where('idEquipo', $partido->idEquipoLocal)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        $clasificacionVisitante = Clasificacion::where('idEquipo', $partido->idEquipoVisitante)
            ->where('idTorneo', $partido->idTorneo)
            ->first();

        if (!$clasificacionLocal || !$clasificacionVisitante) {
            return;
        }

        // Revertir estadísticas equipo local
        $clasificacionLocal->partidosJugados = max(0, $clasificacionLocal->partidosJugados - 1);
        $clasificacionLocal->golesFavor = max(0, $clasificacionLocal->golesFavor - $golesLocal);
        $clasificacionLocal->golesContra = max(0, $clasificacionLocal->golesContra - $golesVisitante);

        // Revertir estadísticas equipo visitante
        $clasificacionVisitante->partidosJugados = max(0, $clasificacionVisitante->partidosJugados - 1);
        $clasificacionVisitante->golesFavor = max(0, $clasificacionVisitante->golesFavor - $golesVisitante);
        $clasificacionVisitante->golesContra = max(0, $clasificacionVisitante->golesContra - $golesLocal);

        // Revertir victoria, empate o derrota
        if ($golesLocal > $golesVisitante) {
            // Revertir victoria local
            $clasificacionLocal->victorias = max(0, $clasificacionLocal->victorias - 1);
            $clasificacionLocal->puntos = max(0, $clasificacionLocal->puntos - 3);
            $clasificacionVisitante->derrotas = max(0, $clasificacionVisitante->derrotas - 1);
        } elseif ($golesLocal < $golesVisitante) {
            // Revertir victoria visitante
            $clasificacionVisitante->victorias = max(0, $clasificacionVisitante->victorias - 1);
            $clasificacionVisitante->puntos = max(0, $clasificacionVisitante->puntos - 3);
            $clasificacionLocal->derrotas = max(0, $clasificacionLocal->derrotas - 1);
        } else {
            // Revertir empate
            $clasificacionLocal->empates = max(0, $clasificacionLocal->empates - 1);
            $clasificacionLocal->puntos = max(0, $clasificacionLocal->puntos - 1);
            $clasificacionVisitante->empates = max(0, $clasificacionVisitante->empates - 1);
            $clasificacionVisitante->puntos = max(0, $clasificacionVisitante->puntos - 1);
        }

        $clasificacionLocal->save();
        $clasificacionVisitante->save();
    }
}
