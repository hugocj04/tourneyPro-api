<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    use AuthorizesRequests;
    
    public function index()
    {
        $this->authorize('viewAny', Usuario::class);
        
        return response()->json(Usuario::paginate(15));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Usuario::class);
        
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'foto_perfil' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'contraseña' => ['required', 'string', 'min:8'],
            'rol' => ['sometimes', 'in:admin,usuario'],
            'fechaRegistro' => ['required', 'date'],
        ]);

        $usuario = Usuario::create($validated);

        return response()->json($usuario, 201);
    }

    public function show(Usuario $usuario)
    {
        $this->authorize('view', $usuario);
        
        return response()->json($usuario->load(['torneos', 'notificaciones', 'jugador']));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $this->authorize('update', $usuario);
        
        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'apellidos' => ['sometimes', 'required', 'string', 'max:255'],
            'telefono' => ['sometimes', 'nullable', 'string', 'max:20'],
            'foto_perfil' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->idUsuario, 'idUsuario'),
            ],
            'contraseña' => ['sometimes', 'required', 'string', 'min:8'],
            'rol' => ['sometimes', 'in:admin,usuario'],
            'fechaRegistro' => ['sometimes', 'required', 'date'],
        ]);

        $usuario->update($validated);

        return response()->json($usuario);
    }

    public function destroy(Usuario $usuario)
    {
        $this->authorize('delete', $usuario);
        
        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
