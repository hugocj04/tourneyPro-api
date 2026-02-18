<?php

namespace App\Http\Controllers;

use App\Models\EventoPartido;
use App\Models\EstadisticaJugador;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EventoPartidoController extends Controller
{
    use AuthorizesRequests;
    
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
            'tipoEvento' => ['required', 'in:gol,tarjeta_amarilla,tarjeta_roja,cambio,autogol,penal_fallado,lesion'],
            'minuto' => ['required', 'integer', 'min:0', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);
        
        $evento = EventoPartido::create($validated);
        
        // Actualizar estadísticas automáticamente
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
        // Revertir estadísticas anteriores si hay jugador
        if ($eventoPartido->idJugador) {
            $this->revertirEstadisticas($eventoPartido);
        }
        
        $validated = $request->validate([
            'idJugador' => ['sometimes', 'nullable', 'exists:jugadores,idJugador'],
            'tipoEvento' => ['sometimes', 'in:gol,tarjeta_amarilla,tarjeta_roja,cambio,autogol,penal_fallado,lesion'],
            'minuto' => ['sometimes', 'integer', 'min:0', 'max:120'],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);
        
        $eventoPartido->update($validated);
        
        // Aplicar nuevas estadísticas
        if ($eventoPartido->idJugador) {
            $this->actualizarEstadisticas($eventoPartido);
        }
        
        return response()->json($eventoPartido->load(['partido', 'jugador', 'equipo']));
    }
    
    public function destroy(EventoPartido $eventoPartido)
    {
        // Revertir estadísticas antes de eliminar
        if ($eventoPartido->idJugador) {
            $this->revertirEstadisticas($eventoPartido);
        }
        
        $eventoPartido->delete();
        
        return response()->json([
            'message' => 'Evento eliminado correctamente'
        ]);
    }
    
    /**
     * Actualizar estadísticas del jugador según el tipo de evento
     */
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
                'asistencias' => 0,
                'tarjetasAmarillas' => 0,
                'tarjetasRojas' => 0,
                'minutosJugados' => 0,
                'partidosJugados' => 0,
            ]
        );
        
        switch ($evento->tipoEvento) {
            case 'gol':
            case 'autogol':
                $estadistica->goles++;
                break;
            case 'tarjeta_amarilla':
                $estadistica->tarjetasAmarillas++;
                break;
            case 'tarjeta_roja':
                $estadistica->tarjetasRojas++;
                break;
        }
        
        $estadistica->save();
    }
    
    /**
     * Revertir estadísticas del jugador según el tipo de evento
     */
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
        
        switch ($evento->tipoEvento) {
            case 'gol':
            case 'autogol':
                $estadistica->goles = max(0, $estadistica->goles - 1);
                break;
            case 'tarjeta_amarilla':
                $estadistica->tarjetasAmarillas = max(0, $estadistica->tarjetasAmarillas - 1);
                break;
            case 'tarjeta_roja':
                $estadistica->tarjetasRojas = max(0, $estadistica->tarjetasRojas - 1);
                break;
        }
        
        $estadistica->save();
    }
}
