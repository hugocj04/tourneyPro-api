<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipo::query();
        
        // Filtrar por categoría
        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        
        // Búsqueda por nombre
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        
        // Ordenamiento
        $sortBy = $request->get('sortBy', 'nombre');
        $sortOrder = $request->get('sortOrder', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        return response()->json($query->get());
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
        return response()->json([
            'success' => true,
            'data' => $equipo->load(['jugadores', 'clasificaciones', 'partidosLocales', 'partidosVisitantes']),
        ]);
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
