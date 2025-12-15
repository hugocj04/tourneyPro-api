<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdministradorController extends Controller
{
    public function index()
    {
        return response()->json(Administrador::with('usuario')->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'telefonoContacto' => ['required', 'string', 'max:255'],
            'organizacion' => ['required', 'string', 'max:255'],
            'idUsuario' => ['required', 'exists:usuarios,idUsuario', 'unique:administradores,idUsuario'],
        ]);

        $administrador = Administrador::create($validated);

        return response()->json($administrador->load('usuario'), 201);
    }

    public function show(Administrador $administrador)
    {
        return response()->json($administrador->load(['usuario', 'torneos', 'notificaciones']));
    }

    public function update(Request $request, Administrador $administrador)
    {
        $validated = $request->validate([
            'telefonoContacto' => ['sometimes', 'required', 'string', 'max:255'],
            'organizacion' => ['sometimes', 'required', 'string', 'max:255'],
            'idUsuario' => [
                'sometimes',
                'required',
                'exists:usuarios,idUsuario',
                Rule::unique('administradores', 'idUsuario')->ignore($administrador->idAdmin, 'idAdmin'),
            ],
        ]);

        $administrador->update($validated);

        return response()->json($administrador->load('usuario'));
    }

    public function destroy(Administrador $administrador)
    {
        $administrador->delete();

        return response()->json(['message' => 'Administrador eliminado correctamente']);
    }
}
