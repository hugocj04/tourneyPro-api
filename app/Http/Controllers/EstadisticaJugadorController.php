<?php

namespace App\Http\Controllers;

use App\Models\EstadisticaJugador;
use Illuminate\Http\Request;

class EstadisticaJugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Filtrable por idTorneo
     */
    public function index(Request $request)
    {
        $query = EstadisticaJugador::with(['jugador', 'torneo']);

        // Filtrar por torneo si se proporciona
        if ($request->has('idTorneo')) {
            $query->where('idTorneo', $request->idTorneo);
        }

        // Filtrar por jugador si se proporciona
        if ($request->has('idJugador')) {
            $query->where('idJugador', $request->idJugador);
        }

        $estadisticas = $query->get();

        return response()->json([
            'success' => true,
            'data' => $estadisticas,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EstadisticaJugador $estadisticaJugador)
    {
        return response()->json([
            'success' => true,
            'data' => $estadisticaJugador->load(['jugador', 'torneo']),
        ]);
    }

    /**
     * Obtener tabla de goleadores de un torneo
     */
    public function goleadores(Request $request)
    {
        $idTorneo = $request->query('idTorneo');

        if (!$idTorneo) {
            return response()->json([
                'success' => false,
                'message' => 'El parámetro idTorneo es requerido',
            ], 400);
        }

        $goleadores = EstadisticaJugador::with(['jugador', 'torneo'])
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
