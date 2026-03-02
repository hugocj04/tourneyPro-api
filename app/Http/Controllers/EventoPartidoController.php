<?php

namespace App\Http\Controllers;

use App\Models\EventoPartido;
use App\Models\EstadisticaJugador;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EventoPartidoController extends Controller
{
    use AuthorizesRequests;

    public function indexByPartido(\App\Models\Partido $partido)
    {
        $eventos = $partido->eventos()->with(['equipo', 'jugador.usuario'])->orderBy('minuto')->get();
        return response()->json(['success' => true, 'data' => $eventos]);
    }

    public function storeByPartido(Request $request, \App\Models\Partido $partido)
    {
        $validated = $request->validate([
            'idJugador'   => ['nullable', 'exists:jugadores,idJugador'],
            'idEquipo'    => ['required', 'exists:equipos,idEquipo'],
            'tipoEvento'  => ['required', 'in:gol'],
            'minuto'      => ['nullable', 'integer', 'min:0', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);
        $validated['idPartido'] = $partido->idPartido;

        $evento = EventoPartido::create($validated);
        if ($evento->idJugador) {
            $this->actualizarEstadisticas($evento);
        }

        return response()->json(['success' => true, 'data' => $evento->load(['jugador.usuario', 'equipo'])], 201);
    }

    public function index()
    {
        $eventos = EventoPartido::with(['partido', 'jugador', 'equipo'])->paginate(15);
        
        return response()->json($eventos);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idPartido' => ['required', 'exists:partidos,idPartido'],
            'idJugador' => ['nullable', 'exists:jugadores,idJugador'],
            'idEquipo' => ['required', 'exists:equipos,idEquipo'],
            'tipoEvento' => ['required', 'in:gol'],
            'minuto' => ['nullable', 'integer', 'min:0', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);
        
        $evento = EventoPartido::create($validated);
        
        if ($evento->idJugador) {
            $this->actualizarEstadisticas($evento);
        }
        
        return response()->json($evento->load(['partido', 'jugador', 'equipo']), 201);
    }
    
    public function show(EventoPartido $eventoPartido)
    {
        return response()->json($eventoPartido->load(['partido', 'jugador', 'equipo']));
    }
    
    public function update(Request $request, EventoPartido $eventoPartido)
    {
        if ($eventoPartido->idJugador) {
            $this->revertirEstadisticas($eventoPartido);
        }
        
        $validated = $request->validate([
            'idJugador' => ['sometimes', 'nullable', 'exists:jugadores,idJugador'],
            'tipoEvento' => ['sometimes', 'in:gol'],
            'minuto' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:120'],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);
        
        $eventoPartido->update($validated);
        
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
        
        return response()->json($eventoPartido->load(['partido', 'jugador', 'equipo']));
    }
    
    public function destroy(EventoPartido $eventoPartido)
    {
        if ($eventoPartido->idJugador) {
            $this->revertirEstadisticas($eventoPartido);
        }
        
        $eventoPartido->delete();
        
        return response()->json([
            'message' => 'Evento eliminado correctamente'
        ]);
    }
    
    private function actualizarEstadisticas(EventoPartido $evento)
    {
        $evento->load('partido');
        $idTorneo = $evento->partido->idTorneo;
        
        $estadistica = EstadisticaJugador::firstOrCreate(
            [
                'idJugador' => $evento->idJugador,
                'idTorneo' => $idTorneo,
            ],
            [
                'goles' => 0,
                'partidosJugados' => 0,
            ]
        );
        
        if ($evento->tipoEvento === 'gol') {
            $estadistica->goles++;
        }
        
        $estadistica->save();
    }
    
    private function revertirEstadisticas(EventoPartido $evento)
    {
        $evento->load('partido');
        $idTorneo = $evento->partido->idTorneo;
        
        $estadistica = EstadisticaJugador::where('idJugador', $evento->idJugador)
            ->where('idTorneo', $idTorneo)
            ->first();
        
        if (!$estadistica) {
            return;
        }
        
        if ($evento->tipoEvento === 'gol') {
            $estadistica->goles = max(0, $estadistica->goles - 1);
        }

        $estadistica->save();
    }
}
