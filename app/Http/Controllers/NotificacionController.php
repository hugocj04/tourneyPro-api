<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        return response()->json(Notificacion::with(['usuario', 'administrador'])->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'mensaje' => ['required', 'string'],
            'fechaEnvio' => ['required', 'date'],
            'leida' => ['sometimes', 'boolean'],
            'idUsuario' => ['required', 'exists:usuarios,idUsuario'],
            'idAdmin' => ['required', 'exists:administradores,idAdmin'],
        ]);

        $notificacion = Notificacion::create($validated);

        return response()->json($notificacion->load(['usuario', 'administrador']), 201);
    }

    public function show(Notificacion $notificacion)
    {
        return response()->json($notificacion->load(['usuario', 'administrador']));
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $validated = $request->validate([
            'titulo' => ['sometimes', 'required', 'string', 'max:255'],
            'mensaje' => ['sometimes', 'required', 'string'],
            'fechaEnvio' => ['sometimes', 'required', 'date'],
            'leida' => ['sometimes', 'boolean'],
            'idUsuario' => ['sometimes', 'required', 'exists:usuarios,idUsuario'],
            'idAdmin' => ['sometimes', 'required', 'exists:administradores,idAdmin'],
        ]);

        $notificacion->update($validated);

        return response()->json($notificacion->load(['usuario', 'administrador']));
    }

    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();

        return response()->json(['message' => 'Notificación eliminada correctamente']);
    }
}
