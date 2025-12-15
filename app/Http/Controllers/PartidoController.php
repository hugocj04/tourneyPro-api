<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use Illuminate\Http\Request;

class PartidoController extends Controller
{
    public function index()
    {
        return response()->json(Partido::with(['torneo', 'equipoLocal', 'equipoVisitante'])->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'lugar' => ['required', 'string', 'max:255'],
            'resultadoLocal' => ['nullable', 'integer'],
            'resultadoVisitante' => ['nullable', 'integer'],
            'estado' => ['required', 'string', 'max:100'],
            'idTorneo' => ['required', 'exists:torneos,idTorneo'],
            'idEquipoLocal' => ['required', 'exists:equipos,IdEquipo'],
            'idEquipoVisitante' => ['required', 'different:idEquipoLocal', 'exists:equipos,IdEquipo'],
        ]);

        $partido = Partido::create($validated);

        return response()->json($partido->load(['torneo', 'equipoLocal', 'equipoVisitante']), 201);
    }

    public function show(Partido $partido)
    {
        return response()->json($partido->load(['torneo', 'equipoLocal', 'equipoVisitante']));
    }

    public function update(Request $request, Partido $partido)
    {
        $validated = $request->validate([
            'fecha' => ['sometimes', 'required', 'date'],
            'hora' => ['sometimes', 'required', 'date_format:H:i'],
            'lugar' => ['sometimes', 'required', 'string', 'max:255'],
            'resultadoLocal' => ['sometimes', 'nullable', 'integer'],
            'resultadoVisitante' => ['sometimes', 'nullable', 'integer'],
            'estado' => ['sometimes', 'required', 'string', 'max:100'],
            'idTorneo' => ['sometimes', 'required', 'exists:torneos,idTorneo'],
            'idEquipoLocal' => ['sometimes', 'required', 'exists:equipos,IdEquipo'],
            'idEquipoVisitante' => ['sometimes', 'required', 'exists:equipos,IdEquipo'],
        ]);

        if (($validated['idEquipoLocal'] ?? $partido->idEquipoLocal) === ($validated['idEquipoVisitante'] ?? $partido->idEquipoVisitante)) {
            return response()->json(['message' => 'El equipo local y visitante deben ser distintos'], 422);
        }

        $partido->update($validated);

        return response()->json($partido->load(['torneo', 'equipoLocal', 'equipoVisitante']));
    }

    public function destroy(Partido $partido)
    {
        $partido->delete();

        return response()->json(['message' => 'Partido eliminado correctamente']);
    }
}
