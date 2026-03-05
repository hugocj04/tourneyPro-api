<?php

namespace App\Models;

use App\Models\Usuario;
use App\Models\Clasificacion;
use App\Models\Partido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Torneo extends Model
{
    protected $table = 'torneos';

    protected $primaryKey = 'idTorneo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'ubicacion',
        'imagenPortada',
        'categoria',
        'formato',
        'tipoFutbol',
        'maxEquipos',
        'precioInscripcion',
        'fechaInicio',
        'fechaFin',
        'estado',
        'idUsuarioCreador',
    ];

    protected $casts = [
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
        'precioInscripcion' => 'decimal:2',
    ];

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuarioCreador', 'idUsuario');
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Partido::class, 'idTorneo', 'idTorneo');
    }

    public function clasificaciones(): HasMany
    {
        return $this->hasMany(Clasificacion::class, 'idTorneo', 'idTorneo');
    }
    
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionEquipo::class, 'idTorneo', 'idTorneo');
    }
}
