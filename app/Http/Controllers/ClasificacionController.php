<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use Illuminate\Http\Request;

class ClasificacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Clasificacion::with(['equipo', 'torneo'])->ordenadaPorPosicion();
        
        // Permitir filtrar por torneo
        if ($request->has('idTorneo')) {
            $query->where('idTorneo', $request->idTorneo);
        }
        
        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'puntos' => ['sometimes', 'integer', 'min:0'],
            'partidosJugados' => ['sometimes', 'integer', 'min:0'],
            'victorias' => ['sometimes', 'integer', 'min:0'],
            'empates' => ['sometimes', 'integer', 'min:0'],
            'derrotas' => ['sometimes', 'integer', 'min:0'],
            'golesFavor' => ['sometimes', 'integer', 'min:0'],
            'golesContra' => ['sometimes', 'integer', 'min:0'],
            'idEquipo' => ['required', 'exists:equipos,idEquipo'],
            'idTorneo' => ['required', 'exists:torneos,idTorneo'],
        ]);

        $clasificacion = Clasificacion::create($validated);

        return response()->json($clasificacion->load(['equipo', 'torneo']), 201);
    }

    public function show(Clasificacion $clasificacion)
    {
        return response()->json($clasificacion->load(['equipo', 'torneo']));
    }

    public function update(Request $request, Clasificacion $clasificacion)
    {
        $validated = $request->validate([
            'puntos' => ['sometimes', 'integer', 'min:0'],
            'partidosJugados' => ['sometimes', 'integer', 'min:0'],
            'victorias' => ['sometimes', 'integer', 'min:0'],
            'empates' => ['sometimes', 'integer', 'min:0'],
            'derrotas' => ['sometimes', 'integer', 'min:0'],
            'golesFavor' => ['sometimes', 'integer', 'min:0'],
            'golesContra' => ['sometimes', 'integer', 'min:0'],
            'idEquipo' => ['sometimes', 'required', 'exists:equipos,idEquipo'],
            'idTorneo' => ['sometimes', 'required', 'exists:torneos,idTorneo'],
        ]);

        $clasificacion->update($validated);

        return response()->json($clasificacion->load(['equipo', 'torneo']));
    }

    public function destroy(Clasificacion $clasificacion)
    {
        $clasificacion->delete();

        return response()->json(['message' => 'Clasificación eliminada correctamente']);
    }
}
