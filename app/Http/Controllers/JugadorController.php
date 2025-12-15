<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JugadorController extends Controller
{
    public function index()
    {
        return response()->json(Jugador::with(['usuario', 'equipo'])->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dorsal' => ['required', 'integer', 'min:0'],
            'posicion' => ['required', 'string', 'max:255'],
            'idUsuario' => ['required', 'exists:usuarios,idUsuario', 'unique:jugadores,idUsuario'],
            'IdEquipo' => ['required', 'exists:equipos,IdEquipo'],
        ]);

        $jugador = Jugador::create($validated);

        return response()->json($jugador->load(['usuario', 'equipo']), 201);
    }

    public function show(Jugador $jugador)
    {
        return response()->json($jugador->load(['usuario', 'equipo']));
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
            'IdEquipo' => ['sometimes', 'required', 'exists:equipos,IdEquipo'],
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
