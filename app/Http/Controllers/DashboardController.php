<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Models\EstadisticaJugador;
use App\Models\Partido;
use App\Models\Torneo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Obtener estadísticas generales de un torneo
     */
    public function torneo(Request $request, $idTorneo)
    {
        $torneo = Torneo::with(['inscripciones', 'partidos', 'clasificaciones'])
            ->findOrFail($idTorneo);

        $estadisticas = [
            'torneo' => $torneo,
            'equipos_inscritos' => $torneo->inscripciones()->count(),
            'equipos_aceptados' => $torneo->inscripciones()->where('estado', 'aceptada')->count(),
            'partidos_totales' => $torneo->partidos()->count(),
            'partidos_jugados' => $torneo->partidos()->whereNotNull('resultadoLocal')->count(),
            'partidos_pendientes' => $torneo->partidos()->whereNull('resultadoLocal')->count(),
            'goles_totales' => $torneo->partidos()
                ->whereNotNull('resultadoLocal')
                ->sum(DB::raw('resultadoLocal + resultadoVisitante')),
        ];

        return response()->json([
            'success' => true,
            'data' => $estadisticas,
        ]);
    }

    /**
     * Obtener tabla de posiciones de un torneo
     */
    public function tablaPosiciones(Request $request, $idTorneo)
    {
        $clasificaciones = Clasificacion::with(['equipo'])
            ->where('idTorneo', $idTorneo)
            ->ordenadaPorPosicion()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clasificaciones,
        ]);
    }

    /**
     * Obtener tabla de goleadores de un torneo
     */
    public function goleadores(Request $request, $idTorneo)
    {
        $goleadores = EstadisticaJugador::with(['jugador.equipo'])
            ->where('idTorneo', $idTorneo)
            ->where('goles', '>', 0)
            ->orderBy('goles', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $goleadores,
        ]);
    }

    /**
     * Obtener equipos con mejor ataque
     */
    public function mejorAtaque(Request $request, $idTorneo)
    {
        $equipos = Clasificacion::with(['equipo'])
            ->where('idTorneo', $idTorneo)
            ->orderBy('golesFavor', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $equipos,
        ]);
    }

    /**
     * Obtener equipos con mejor defensa
     */
    public function mejorDefensa(Request $request, $idTorneo)
    {
        $equipos = Clasificacion::with(['equipo'])
            ->where('idTorneo', $idTorneo)
            ->orderBy('golesContra', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $equipos,
        ]);
    }

    /**
     * Obtener próximos partidos de un torneo
     */
    public function proximosPartidos(Request $request, $idTorneo)
    {
        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->where('idTorneo', $idTorneo)
            ->whereNull('resultadoLocal')
            ->where('fechaHora', '>=', now())
            ->orderBy('fechaHora', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $partidos,
        ]);
    }

    /**
     * Obtener últimos resultados de un torneo
     */
    public function ultimosResultados(Request $request, $idTorneo)
    {
        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->where('idTorneo', $idTorneo)
            ->whereNotNull('resultadoLocal')
            ->orderBy('fechaHora', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $partidos,
        ]);
    }

    /**
     * Obtener estadísticas completas de un equipo en un torneo
     */
    public function estadisticasEquipo(Request $request, $idTorneo, $idEquipo)
    {
        $clasificacion = Clasificacion::with(['equipo'])
            ->where('idTorneo', $idTorneo)
            ->where('idEquipo', $idEquipo)
            ->first();

        if (!$clasificacion) {
            return response()->json([
                'success' => false,
                'message' => 'Equipo no encontrado en el torneo',
            ], 404);
        }

        $partidosLocal = Partido::where('idTorneo', $idTorneo)
            ->where('idEquipoLocal', $idEquipo)
            ->whereNotNull('resultadoLocal')
            ->get();

        $partidosVisitante = Partido::where('idTorneo', $idTorneo)
            ->where('idEquipoVisitante', $idEquipo)
            ->whereNotNull('resultadoLocal')
            ->get();

        $estadisticas = [
            'clasificacion' => $clasificacion,
            'partidos_local' => $partidosLocal->count(),
            'partidos_visitante' => $partidosVisitante->count(),
            'victorias_local' => $partidosLocal->filter(function ($p) {
                return $p->resultadoLocal > $p->resultadoVisitante;
            })->count(),
            'victorias_visitante' => $partidosVisitante->filter(function ($p) {
                return $p->resultadoVisitante > $p->resultadoLocal;
            })->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $estadisticas,
        ]);
    }

    /**
     * Obtener resumen general del sistema
     */
    public function resumenGeneral()
    {
        $estadisticas = [
            'torneos_activos' => Torneo::where('estado', 'activo')->count(),
            'torneos_finalizados' => Torneo::where('estado', 'finalizado')->count(),
            'partidos_hoy' => Partido::whereDate('fechaHora', today())->count(),
            'partidos_esta_semana' => Partido::whereBetween('fechaHora', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $estadisticas,
        ]);
    }
}
