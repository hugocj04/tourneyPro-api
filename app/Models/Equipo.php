<?php

namespace App\Models;

use App\Models\Clasificacion;
use App\Models\Jugador;
use App\Models\Partido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipo extends Model
{
    protected $table = 'equipos';

    protected $primaryKey = 'idEquipo';

    protected $fillable = [
        'nombre',
        'logo',
        'categoria',
    ];

    public function jugadores(): HasMany
    {
        return $this->hasMany(Jugador::class, 'idEquipo', 'idEquipo');
    }

    public function clasificaciones(): HasMany
    {
        return $this->hasMany(Clasificacion::class, 'idEquipo', 'idEquipo');
    }

    public function partidosLocales(): HasMany
    {
        return $this->hasMany(Partido::class, 'idEquipoLocal', 'idEquipo');
    }

    public function partidosVisitantes(): HasMany
    {
        return $this->hasMany(Partido::class, 'idEquipoVisitante', 'idEquipo');
    }
    
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionEquipo::class, 'idEquipo', 'idEquipo');
    }
}
