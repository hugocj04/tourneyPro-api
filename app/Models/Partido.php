<?php

namespace App\Models;

use App\Models\Equipo;
use App\Models\Torneo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partido extends Model
{
    protected $table = 'partidos';

    protected $primaryKey = 'idPartido';

    protected $fillable = [
        'fecha',
        'hora',
        'lugar',
        'resultadoLocal',
        'resultadoVisitante',
        'estado',
        'idTorneo',
        'idEquipoLocal',
        'idEquipoVisitante',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class, 'idTorneo', 'idTorneo');
    }

    public function equipoLocal(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipoLocal', 'IdEquipo');
    }

    public function equipoVisitante(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipoVisitante', 'IdEquipo');
    }
}
