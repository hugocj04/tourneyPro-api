<?php

namespace App\Policies;

use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class UsuarioPolicy
{
    
    public function viewAny(Usuario $usuario): bool
    {
        
        return $usuario->rol === 'admin';
    }

    public function view(Usuario $usuario, Usuario $model): bool
    {
        
        return $usuario->rol === 'admin' || $usuario->idUsuario === $model->idUsuario;
    }

    public function create(Usuario $usuario): bool
    {
        
        return $usuario->rol === 'admin';
    }

    public function update(Usuario $usuario, Usuario $model): bool
    {
        
        return $usuario->rol === 'admin' || $usuario->idUsuario === $model->idUsuario;
    }

    public function delete(Usuario $usuario, Usuario $model): bool
    {
        
        return $usuario->rol === 'admin';
    }

    public function restore(Usuario $usuario, Usuario $model): bool
    {
        return $usuario->rol === 'admin';
    }

    public function forceDelete(Usuario $usuario, Usuario $model): bool
    {
        return $usuario->rol === 'admin';
    }
}
