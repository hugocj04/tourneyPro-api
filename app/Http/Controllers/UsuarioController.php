<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json(Usuario::paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'contraseña' => ['required', 'string', 'min:8'],
            'fechaRegistro' => ['required', 'date'],
        ]);

        $usuario = Usuario::create($validated);

        return response()->json($usuario, 201);
    }

    public function show(Usuario $usuario)
    {
        return response()->json($usuario->load(['administrador', 'notificaciones', 'jugador']));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'apellidos' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->idUsuario, 'idUsuario'),
            ],
            'contraseña' => ['sometimes', 'required', 'string', 'min:8'],
            'fechaRegistro' => ['sometimes', 'required', 'date'],
        ]);

        $usuario->update($validated);

        return response()->json($usuario);
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
