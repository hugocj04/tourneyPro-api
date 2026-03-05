<?php

namespace App\Policies;

use App\Models\Torneo;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class TorneoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        // Todos pueden ver lista de torneos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario, Torneo $torneo): bool
    {
        // Todos los usuarios autenticados pueden ver cualquier torneo
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        // Todos los usuarios autenticados pueden crear torneos
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario, Torneo $torneo): bool
    {
        // Admin puede editar todos, usuario solo los suyos
        return $usuario->rol === 'admin' || $torneo->idUsuarioCreador === $usuario->idUsuario;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, Torneo $torneo): bool
    {
        // Admin puede eliminar todos, usuario solo los suyos
        return $usuario->rol === 'admin' || $torneo->idUsuarioCreador === $usuario->idUsuario;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, Torneo $torneo): bool
    {
        return $usuario->rol === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, Torneo $torneo): bool
    {
        return $usuario->rol === 'admin';
    }
}
