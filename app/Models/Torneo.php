<?php

namespace App\Models;

use App\Models\Administrador;
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
        'deporte',
        'categoria',
        'formato',
        'fechaInicio',
        'fechaFin',
        'estado',
        'idAdmin',
    ];

    protected $casts = [
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
    ];

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'idAdmin', 'idAdmin');
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Partido::class, 'idTorneo', 'idTorneo');
    }

    public function clasificaciones(): HasMany
    {
        return $this->hasMany(Clasificacion::class, 'idTorneo', 'idTorneo');
    }
}
