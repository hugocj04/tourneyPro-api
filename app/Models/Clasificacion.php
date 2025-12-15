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

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipo', 'IdEquipo');
    }

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class, 'idTorneo', 'idTorneo');
    }
}
