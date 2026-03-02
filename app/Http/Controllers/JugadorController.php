<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JugadorController extends Controller
{
    public function index(Request $request)
    {
        $query = Jugador::with(['usuario', 'equipo']);

        if ($request->has('idEquipo')) {
            $query->where('idEquipo', $request->idEquipo);
        }

        return response()->json($query->paginate(100));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dorsal' => ['required', 'integer', 'min:0'],
            'posicion' => ['required', 'string', 'max:255'],
            'idUsuario' => ['required', 'exists:usuarios,idUsuario', 'unique:jugadores,idUsuario'],
            'idEquipo' => ['required', 'exists:equipos,idEquipo'],
        ]);

        $jugador = Jugador::create($validated);

        return response()->json($jugador->load(['usuario', 'equipo']), 201);
    }

    public function show(Jugador $jugador)
    {
        return response()->json([
            'success' => true,
            'data' => $jugador->load(['usuario', 'equipo']),
        ]);
    }

    public function update(Request $request, Jugador $jugador)
    {
        $validated = $request->validate([
            'dorsal' => ['sometimes', 'required', 'integer', 'min:0'],
            'posicion' => ['sometimes', 'required', 'string', 'max:255'],
            'idUsuario' => [
                'sometimes',
                'required',
                'exists:usuarios,idUsuario',
                Rule::unique('jugadores', 'idUsuario')->ignore($jugador->id, 'id'),
            ],
            'idEquipo' => ['sometimes', 'required', 'exists:equipos,idEquipo'],
        ]);

        $jugador->update($validated);

        return response()->json($jugador->load(['usuario', 'equipo']));
    }

    public function destroy(Jugador $jugador)
    {
        $jugador->delete();

        return response()->json(['message' => 'Jugador eliminado correctamente']);
    }
}
