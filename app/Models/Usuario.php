<?php

namespace App\Models;

use App\Models\Jugador;
use App\Models\Torneo;
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
        'telefono',
        'foto_perfil',
        'email',
        'contraseña',
        'rol',
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

    public function torneos(): HasMany
    {
        return $this->hasMany(Torneo::class, 'idUsuarioCreador', 'idUsuario');
    }

    public function jugador(): HasOne
    {
        return $this->hasOne(Jugador::class, 'idUsuario', 'idUsuario');
    }
}
