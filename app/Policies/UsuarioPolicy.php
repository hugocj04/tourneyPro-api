<?php

namespace App\Policies;

use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class UsuarioPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        // Solo admins pueden ver lista de usuarios
        return $usuario->rol === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario, Usuario $model): bool
    {
        // Admin puede ver todos, usuario puede ver su propio perfil
        return $usuario->rol === 'admin' || $usuario->idUsuario === $model->idUsuario;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        // Solo admins pueden crear usuarios directamente
        return $usuario->rol === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario, Usuario $model): bool
    {
        // Admin puede editar todos, usuario puede editar su propio perfil
        return $usuario->rol === 'admin' || $usuario->idUsuario === $model->idUsuario;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, Usuario $model): bool
    {
        // Solo admin puede eliminar usuarios
        return $usuario->rol === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, Usuario $model): bool
    {
        return $usuario->rol === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, Usuario $model): bool
    {
        return $usuario->rol === 'admin';
    }
}
