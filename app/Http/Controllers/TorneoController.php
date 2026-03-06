<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', Torneo::class);
        
        $query = Torneo::query();

        if ($request->has('idUsuarioCreador')) {
            $query->where('idUsuarioCreador', $request->idUsuarioCreador);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->has('tipoFutbol')) {
            $query->where('tipoFutbol', $request->tipoFutbol);
        }
        
        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        
        $sortBy = $request->get('sortBy', 'fechaInicio');
        $sortOrder = $request->get('sortOrder', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = (int) $request->get('per_page', 15);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Torneo::class);
        
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'imagenPortada' => ['nullable', 'string', 'max:500'],
            'categoria' => ['required', 'string', 'max:255'],
            'formato' => ['required', 'string', 'max:255'],
            'tipoFutbol' => ['required', 'in:futbol_5,futbol_7,futbol_11'],
            'maxEquipos' => ['nullable', 'integer', 'min:2'],
            'precioInscripcion' => ['nullable', 'numeric', 'min:0'],
            'fechaInicio' => ['required', 'date'],
            'fechaFin' => ['required', 'date', 'after_or_equal:fechaInicio'],
            'estado' => ['sometimes', 'string', 'max:100'],
        ]);
        
        $validated['estado'] = $validated['estado'] ?? 'pendiente';
        
        $validated['idUsuarioCreador'] = auth()->id();

        $torneo = Torneo::create($validated);

        return response()->json($torneo->load(['usuarioCreador']), 201);
    }

    public function show(Torneo $torneo)
    {
        $this->authorize('view', $torneo);
        
        return response()->json([
            'success' => true,
            'data' => $torneo->load(['usuarioCreador', 'partidos', 'clasificaciones']),
        ]);
    }

    public function update(Request $request, Torneo $torneo)
    {
        $this->authorize('update', $torneo);
        
        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'ubicacion' => ['sometimes', 'nullable', 'string', 'max:255'],
            'imagenPortada' => ['sometimes', 'nullable', 'string', 'max:500'],
            'categoria' => ['sometimes', 'required', 'string', 'max:255'],
            'formato' => ['sometimes', 'required', 'string', 'max:255'],
            'tipoFutbol' => ['sometimes', 'required', 'in:futbol_5,futbol_7,futbol_11'],
            'maxEquipos' => ['sometimes', 'nullable', 'integer', 'min:2'],
            'precioInscripcion' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'fechaInicio' => ['sometimes', 'required', 'date'],
            'fechaFin' => ['sometimes', 'required', 'date', 'after_or_equal:fechaInicio'],
            'estado' => ['sometimes', 'required', 'string', 'max:100'],
        ]);

        $torneo->update($validated);

        return response()->json($torneo->load(['usuarioCreador', 'partidos', 'clasificaciones']));
    }

    public function destroy(Torneo $torneo)
    {
        $this->authorize('delete', $torneo);
        
        $torneo->delete();

        return response()->json(['message' => 'Torneo eliminado correctamente']);
    }
}
