<?php

namespace App\Http\Controllers;

use App\Models\InscripcionEquipo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class InscripcionEquipoController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $query = InscripcionEquipo::with(['torneo', 'equipo']);

        if ($request->has('idTorneo')) {
            $query->where('idTorneo', $request->idTorneo);
        }
        if ($request->has('idEquipo')) {
            $query->where('idEquipo', $request->idEquipo);
        }
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        return response()->json($query->paginate(15));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idTorneo' => ['required', 'exists:torneos,idTorneo'],
            'idEquipo' => ['required', 'exists:equipos,idEquipo'],
            'montoAbonado' => ['nullable', 'numeric', 'min:0'],
        ]);
        
        $existe = InscripcionEquipo::where('idTorneo', $validated['idTorneo'])
            ->where('idEquipo', $validated['idEquipo'])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'message' => 'Este equipo ya está inscrito en el torneo'
            ], 422);
        }
        
        $inscripcion = InscripcionEquipo::create($validated);
        $inscripcion->refresh();
        
        return response()->json($inscripcion->load(['torneo', 'equipo']), 201);
    }
    
    public function show(InscripcionEquipo $inscripcionEquipo)
    {
        return response()->json([
            'success' => true,
            'data' => $inscripcionEquipo->load(['torneo', 'equipo']),
        ]);
    }
    
    public function update(Request $request, InscripcionEquipo $inscripcionEquipo)
    {
        $validated = $request->validate([
            'estado' => ['sometimes', 'in:pendiente,aceptada,rechazada'],
            'montoAbonado' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ]);
        
        $inscripcionEquipo->update($validated);
        
        return response()->json($inscripcionEquipo->load(['torneo', 'equipo']));
    }
    
    public function destroy(InscripcionEquipo $inscripcionEquipo)
    {
        $inscripcionEquipo->delete();
        
        return response()->json([
            'message' => 'Inscripción eliminada correctamente'
        ]);
    }
}
