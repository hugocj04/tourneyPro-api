<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use Illuminate\Http\Request;

class PartidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Partido::with(['torneo', 'equipoLocal', 'equipoVisitante']);
        
        // Filtrar por torneo
        if ($request->has('idTorneo')) {
            $query->where('idTorneo', $request->idTorneo);
        }
        
        // Filtrar por equipo (local o visitante)
        if ($request->has('idEquipo')) {
            $query->where(function($q) use ($request) {
                $q->where('idEquipoLocal', $request->idEquipo)
                  ->orWhere('idEquipoVisitante', $request->idEquipo);
            });
        }
        
        // Filtrar por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Filtrar por fecha
        if ($request->has('fecha')) {
            $query->whereDate('fechaHora', $request->fecha);
        }
        
        // Filtrar partidos jugados o pendientes
        if ($request->has('jugado')) {
            if ($request->jugado === 'true' || $request->jugado === '1') {
                $query->whereNotNull('resultadoLocal');
            } else {
                $query->whereNull('resultadoLocal');
            }
        }
        
        // Ordenamiento
        $sortBy = $request->get('sortBy', 'fechaHora');
        $sortOrder = $request->get('sortOrder', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fechaHora' => ['required', 'date'],
            'lugar' => ['required', 'string', 'max:255'],
            'resultadoLocal' => ['nullable', 'integer'],
            'resultadoVisitante' => ['nullable', 'integer'],
            'estado' => ['required', 'string', 'max:100'],
            'idTorneo' => ['required', 'exists:torneos,idTorneo'],
            'idEquipoLocal' => ['required', 'exists:equipos,idEquipo'],
            'idEquipoVisitante' => ['required', 'different:idEquipoLocal', 'exists:equipos,idEquipo'],
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
            'fechaHora' => ['sometimes', 'required', 'date'],
            'lugar' => ['sometimes', 'required', 'string', 'max:255'],
            'resultadoLocal' => ['sometimes', 'nullable', 'integer'],
            'resultadoVisitante' => ['sometimes', 'nullable', 'integer'],
            'estado' => ['sometimes', 'required', 'string', 'max:100'],
            'idTorneo' => ['sometimes', 'required', 'exists:torneos,idTorneo'],
            'idEquipoLocal' => ['sometimes', 'required', 'exists:equipos,idEquipo'],
            'idEquipoVisitante' => ['sometimes', 'required', 'exists:equipos,idEquipo'],
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
