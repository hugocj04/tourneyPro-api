<?php

namespace App\Models;

use App\Models\Administrador;
use App\Models\Jugador;
use App\Models\Notificacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'idUsuario';

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'contraseña',
        'fechaRegistro',
    ];

    protected $hidden = ['contraseña'];

    protected $casts = [
        'fechaRegistro' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (Usuario $usuario): void {
            // Hash password when creating or updating
            if ($usuario->isDirty('contraseña')) {
                $usuario->contraseña = Hash::make($usuario->contraseña);
            }
        });
    }

    public function administrador(): HasOne
    {
        return $this->hasOne(Administrador::class, 'idUsuario', 'idUsuario');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'idUsuario', 'idUsuario');
    }

    public function jugador(): HasOne
    {
        return $this->hasOne(Jugador::class, 'idUsuario', 'idUsuario');
    }
}
