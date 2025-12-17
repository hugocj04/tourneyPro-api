<?php

namespace App\Http\Controllers;

use App\Models\Secuencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SecuenciaController extends Controller
{
    public function index()
    {
        return response()->json(Secuencia::paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:secuencias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'valor' => ['nullable', 'integer', 'min:0'],
        ]);

        $secuencia = Secuencia::create($validated);

        return response()->json($secuencia, 201);
    }

    public function show(Secuencia $secuencia)
    {
        return response()->json($secuencia);
    }

    public function update(Request $request, Secuencia $secuencia)
    {
        $validated = $request->validate([
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('secuencias', 'nombre')->ignore($secuencia->idSecuencia, 'idSecuencia'),
            ],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:500'],
            'valor' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        $secuencia->update($validated);

        return response()->json($secuencia);
    }

    public function destroy(Secuencia $secuencia)
    {
        $secuencia->delete();

        return response()->json(['message' => 'Secuencia eliminada correctamente']);
    }
}