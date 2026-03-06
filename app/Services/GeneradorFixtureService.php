<?php

namespace App\Services;

use App\Models\Partido;
use App\Models\Torneo;
use Carbon\Carbon;

class GeneradorFixtureService
{
    
    public function generarFixture(
        int $idTorneo,
        string $fechaInicio,
        int $diasEntreFechas = 7,
        string $horaInicio = '15:00',
        string $lugar = 'Por definir'
    ): array {
        $torneo = Torneo::findOrFail($idTorneo);

        $equipos = $torneo->inscripciones()
            ->where('estado', 'aceptada')
            ->with('equipo')
            ->get()
            ->pluck('equipo')
            ->toArray();

        if (count($equipos) < 2) {
            throw new \Exception('Se necesitan al menos 2 equipos inscritos y aceptados para generar fixture');
        }

        $fechasCompletas = $this->algoritmRoundRobin($equipos);

        $existentesSet = Partido::where('idTorneo', $idTorneo)
            ->get(['idEquipoLocal', 'idEquipoVisitante'])
            ->mapWithKeys(function ($p) {
                $key = min($p->idEquipoLocal, $p->idEquipoVisitante)
                     . '-'
                     . max($p->idEquipoLocal, $p->idEquipoVisitante);
                return [$key => true];
            })
            ->toArray();

        $partidosCreados = [];
        $fechaActual = Carbon::parse($fechaInicio);

        foreach ($fechasCompletas as $fecha) {
            foreach ($fecha as $partido) {
                $key = min($partido['local'], $partido['visitante'])
                     . '-'
                     . max($partido['local'], $partido['visitante']);

                if (!isset($existentesSet[$key])) {
                    $fechaHora = $fechaActual->copy()->setTimeFromTimeString($horaInicio);

                    $partidoCreado = Partido::create([
                        'fechaHora'          => $fechaHora,
                        'lugar'              => $lugar,
                        'resultadoLocal'     => null,
                        'resultadoVisitante' => null,
                        'estado'             => 'programado',
                        'idTorneo'           => $idTorneo,
                        'idEquipoLocal'      => $partido['local'],
                        'idEquipoVisitante'  => $partido['visitante'],
                    ]);

                    $partidosCreados[] = $partidoCreado;
                }
            }

            $fechaActual->addDays($diasEntreFechas);
        }

        $totalFaltaban = count($partidosCreados);
        $mensaje = $totalFaltaban > 0
            ? "Se han creado {$totalFaltaban} partido(s) que faltaban en el fixture"
            : 'El fixture ya estaba completo, no faltaba ningún partido';

        return [
            'success'          => true,
            'message'          => $mensaje,
            'partidos_creados' => $totalFaltaban,
            'fechas'           => count($fechasCompletas),
            'partidos'         => $partidosCreados,
        ];
    }

    private function algoritmRoundRobin(array $equipos): array
    {
        $numEquipos = count($equipos);
        
        if ($numEquipos % 2 !== 0) {
            $equipos[] = null; 
            $numEquipos++;
        }
        
        $partidos = [];
        $numFechas = $numEquipos - 1;
        $partidosPorFecha = $numEquipos / 2;
        
        for ($fecha = 0; $fecha < $numFechas; $fecha++) {
            $fechaPartidos = [];
            
            for ($partido = 0; $partido < $partidosPorFecha; $partido++) {
                $local = ($fecha + $partido) % ($numEquipos - 1);
                $visitante = ($numEquipos - 1 - $partido + $fecha) % ($numEquipos - 1);
                
                if ($partido == 0) {
                    $visitante = $numEquipos - 1;
                }
                
                if ($fecha % 2 == 1) {
                    $temp = $local;
                    $local = $visitante;
                    $visitante = $temp;
                }
                
                if ($equipos[$local] !== null && $equipos[$visitante] !== null) {
                    $fechaPartidos[] = [
                        'local' => $equipos[$local]['idEquipo'],
                        'visitante' => $equipos[$visitante]['idEquipo'],
                    ];
                }
            }
            
            if (count($fechaPartidos) > 0) {
                $partidos[] = $fechaPartidos;
            }
        }
        
        return $partidos;
    }

    public function limpiarFixture(int $idTorneo): bool
    {
        Partido::where('idTorneo', $idTorneo)
            ->where('estado', 'programado')
            ->where('resultadoLocal', null)
            ->where('resultadoVisitante', null)
            ->delete();
        
        return true;
    }
}
