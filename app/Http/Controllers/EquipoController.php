<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index()
    {
        return response()->json(Equipo::paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:255'],
        ]);

        $equipo = Equipo::create($validated);

        return response()->json($equipo, 201);
    }

    public function show(Equipo $equipo)
    {
        return response()->json($equipo->load(['jugadores', 'clasificaciones', 'partidosLocales', 'partidosVisitantes']));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'logo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'categoria' => ['sometimes', 'required', 'string', 'max:255'],
        ]);

        $equipo->update($validated);

        return response()->json($equipo);
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return response()->json(['message' => 'Equipo eliminado correctamente']);
    }
}
