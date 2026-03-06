<?php

namespace App\Http\Controllers;

use App\Models\EstadisticaJugador;
use Illuminate\Http\Request;

class EstadisticaJugadorController extends Controller
{
    
    public function index(Request $request)
    {
        $query = EstadisticaJugador::with(['jugador', 'torneo']);

        if ($request->has('idTorneo')) {
            $query->where('idTorneo', $request->idTorneo);
        }

        if ($request->has('idJugador')) {
            $query->where('idJugador', $request->idJugador);
        }

        $estadisticas = $query->get();

        return response()->json([
            'success' => true,
            'data' => $estadisticas,
        ]);
    }

    public function show(EstadisticaJugador $estadisticaJugador)
    {
        return response()->json([
            'success' => true,
            'data' => $estadisticaJugador->load(['jugador', 'torneo']),
        ]);
    }

    public function goleadores(Request $request)
    {
        $idTorneo = $request->query('idTorneo');

        if (!$idTorneo) {
            return response()->json([
                'success' => false,
                'message' => 'El parámetro idTorneo es requerido',
            ], 400);
        }

        $goleadores = EstadisticaJugador::with(['jugador.usuario', 'jugador.equipo', 'torneo'])
            ->where('idTorneo', $idTorneo)
            ->orderBy('goles', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $goleadores,
        ]);
    }

}
