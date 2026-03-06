<?php

namespace App\Policies;

use App\Models\Torneo;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class TorneoPolicy
{
    
    public function viewAny(Usuario $usuario): bool
    {
        
        return true;
    }

    public function view(Usuario $usuario, Torneo $torneo): bool
    {
        
        return true;
    }

    public function create(Usuario $usuario): bool
    {
        
        return true;
    }

    public function update(Usuario $usuario, Torneo $torneo): bool
    {
        
        return $usuario->rol === 'admin' || $torneo->idUsuarioCreador === $usuario->idUsuario;
    }

    public function delete(Usuario $usuario, Torneo $torneo): bool
    {
        
        return $usuario->rol === 'admin' || $torneo->idUsuarioCreador === $usuario->idUsuario;
    }

    public function restore(Usuario $usuario, Torneo $torneo): bool
    {
        return $usuario->rol === 'admin';
    }

    public function forceDelete(Usuario $usuario, Torneo $torneo): bool
    {
        return $usuario->rol === 'admin';
    }
}
