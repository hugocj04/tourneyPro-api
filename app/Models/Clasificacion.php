<?php

namespace App\Models;

use App\Models\Equipo;
use App\Models\Torneo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clasificacion extends Model
{
    protected $table = 'clasificaciones';

    protected $primaryKey = 'idClasificacion';

    protected $fillable = [
        'puntos',
        'partidosJugados',
        'victorias',
        'empates',
        'derrotas',
        'golesFavor',
        'golesContra',
        'idEquipo',
        'idTorneo',
    ];

    protected $appends = ['diferencia_goles'];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipo', 'idEquipo');
    }

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class, 'idTorneo', 'idTorneo');
    }

    /**
     * Accessor para diferencia de goles
     */
    public function getDiferenciaGolesAttribute(): int
    {
        return $this->golesFavor - $this->golesContra;
    }

    /**
     * Scope para ordenar por clasificación
     */
    public function scopeOrdenadaPorPosicion($query)
    {
        return $query->orderBy('puntos', 'desc')
                     ->orderBy('golesFavor', 'desc')
                     ->orderBy('golesContra', 'asc');
    }
}
