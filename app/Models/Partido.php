<?php

namespace App\Models;

use App\Models\Equipo;
use App\Models\Torneo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partido extends Model
{
    // Preserve camelCase relation names in JSON (equipoLocal, equipoVisitante)
    public static $snakeAttributes = false;

    protected $table = 'partidos';

    protected $primaryKey = 'idPartido';

    protected $fillable = [
        'fechaHora',
        'lugar',
        'resultadoLocal',
        'resultadoVisitante',
        'estado',
        'idTorneo',
        'idEquipoLocal',
        'idEquipoVisitante',
    ];

    protected $casts = [
        'fechaHora' => 'datetime',
    ];

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class, 'idTorneo', 'idTorneo');
    }

    public function equipoLocal(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipoLocal', 'idEquipo');
    }

    public function equipoVisitante(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipoVisitante', 'idEquipo');
    }
    
    public function eventos(): HasMany
    {
        return $this->hasMany(EventoPartido::class, 'idPartido', 'idPartido');
    }
}
