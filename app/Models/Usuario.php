<?php

namespace App\Models;

use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Jugador;
use App\Models\Notificacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'idUsuario';

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'contraseña',
        'fechaRegistro',
    ];

    protected $hidden = ['contraseña', 'remember_token'];

    protected $casts = [
        'fechaRegistro' => 'date',
        'email_verified_at' => 'datetime',
        'contraseña' => 'hashed',
    ];

    public function getAuthPasswordName()
    {
        return 'contraseña';
    }

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function administrador(): HasOne
    {
        return $this->hasOne(Administrador::class, 'idUsuario', 'idUsuario');
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'idUsuario', 'idUsuario');
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
