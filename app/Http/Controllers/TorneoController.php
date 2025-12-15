<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    public function index()
    {
        return response()->json(Torneo::with(['administrador', 'partidos', 'clasificaciones'])->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'deporte' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:255'],
            'formato' => ['required', 'string', 'max:255'],
            'fechaInicio' => ['required', 'date'],
            'fechaFin' => ['required', 'date', 'after_or_equal:fechaInicio'],
            'estado' => ['required', 'string', 'max:100'],
            'idAdmin' => ['required', 'exists:administradores,idAdmin'],
        ]);

        $torneo = Torneo::create($validated);

        return response()->json($torneo->load(['administrador']), 201);
    }

    public function show(Torneo $torneo)
    {
        return response()->json($torneo->load(['administrador', 'partidos', 'clasificaciones']));
    }

    public function update(Request $request, Torneo $torneo)
    {
        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'deporte' => ['sometimes', 'required', 'string', 'max:255'],
            'categoria' => ['sometimes', 'required', 'string', 'max:255'],
            'formato' => ['sometimes', 'required', 'string', 'max:255'],
            'fechaInicio' => ['sometimes', 'required', 'date'],
            'fechaFin' => ['sometimes', 'required', 'date', 'after_or_equal:fechaInicio'],
            'estado' => ['sometimes', 'required', 'string', 'max:100'],
            'idAdmin' => ['sometimes', 'required', 'exists:administradores,idAdmin'],
        ]);

        $torneo->update($validated);

        return response()->json($torneo->load(['administrador', 'partidos', 'clasificaciones']));
    }

    public function destroy(Torneo $torneo)
    {
        $torneo->delete();

        return response()->json(['message' => 'Torneo eliminado correctamente']);
    }
}
