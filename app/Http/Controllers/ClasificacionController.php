<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use Illuminate\Http\Request;

class ClasificacionController extends Controller
{
    public function index()
    {
        return response()->json(Clasificacion::with(['equipo', 'torneo'])->paginate(15));
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
            'idEquipo' => ['required', 'exists:equipos,IdEquipo'],
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
            'idEquipo' => ['sometimes', 'required', 'exists:equipos,IdEquipo'],
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
