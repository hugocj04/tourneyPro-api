<?php

namespace App\Services;

use App\Models\Partido;
use App\Models\Torneo;
use Carbon\Carbon;

class GeneradorFixtureService
{
    /**
     * Generar fixture completo para un torneo
     * Usa algoritmo Round-Robin para torneos con todos contra todos
     * 
     * @param int $idTorneo
     * @param string $fechaInicio Fecha de inicio del torneo (Y-m-d)
     * @param int $diasEntreFechas Días entre cada fecha
     * @param string $horaInicio Hora de inicio de los partidos (H:i)
     * @param string $lugar Lugar por defecto para los partidos
     * @return array
     */
    public function generarFixture(
        int $idTorneo,
        string $fechaInicio,
        int $diasEntreFechas = 7,
        string $horaInicio = '15:00',
        string $lugar = 'Por definir'
    ): array {
        $torneo = Torneo::findOrFail($idTorneo);

        // Obtener equipos del torneo (a través de inscripciones aceptadas)
        $equipos = $torneo->inscripciones()
            ->where('estado', 'aceptada')
            ->with('equipo')
            ->get()
            ->pluck('equipo')
            ->toArray();

        if (count($equipos) < 2) {
            throw new \Exception('Se necesitan al menos 2 equipos inscritos y aceptados para generar fixture');
        }

        // Calcular el fixture completo según Round-Robin
        $fechasCompletas = $this->algoritmRoundRobin($equipos);

        // Construir conjunto de partidos ya existentes (independiente de local/visitante)
        $existentesSet = Partido::where('idTorneo', $idTorneo)
            ->get(['idEquipoLocal', 'idEquipoVisitante'])
            ->mapWithKeys(function ($p) {
                $key = min($p->idEquipoLocal, $p->idEquipoVisitante)
                     . '-'
                     . max($p->idEquipoLocal, $p->idEquipoVisitante);
                return [$key => true];
            })
            ->toArray();

        // Crear solo los partidos que faltan, respetando la fecha de cada jornada
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

    /**
     * Algoritmo Round-Robin para generar partidos
     * Todos contra todos, ida y vuelta
     * 
     * @param array $equipos
     * @return array
     */
    private function algoritmRoundRobin(array $equipos): array
    {
        $numEquipos = count($equipos);
        
        // Si el número de equipos es impar, agregamos un "bye" (equipo fantasma)
        if ($numEquipos % 2 !== 0) {
            $equipos[] = null; // null representa el "bye"
            $numEquipos++;
        }
        
        $partidos = [];
        $numFechas = $numEquipos - 1;
        $partidosPorFecha = $numEquipos / 2;
        
        // Generar partidos para cada fecha
        for ($fecha = 0; $fecha < $numFechas; $fecha++) {
            $fechaPartidos = [];
            
            for ($partido = 0; $partido < $partidosPorFecha; $partido++) {
                $local = ($fecha + $partido) % ($numEquipos - 1);
                $visitante = ($numEquipos - 1 - $partido + $fecha) % ($numEquipos - 1);
                
                // Si es el último partido de la fecha, uno de los equipos es el fijo
                if ($partido == 0) {
                    $visitante = $numEquipos - 1;
                }
                
                // Alternar local y visitante
                if ($fecha % 2 == 1) {
                    $temp = $local;
                    $local = $visitante;
                    $visitante = $temp;
                }
                
                // Solo agregar si ninguno de los equipos es "bye"
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

    /**
     * Limpiar fixture existente de un torneo
     * 
     * @param int $idTorneo
     * @return bool
     */
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
