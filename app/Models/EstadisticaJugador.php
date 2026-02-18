<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadisticaJugador extends Model
{
    protected $table = 'estadisticas_jugadores';
    
    protected $primaryKey = 'idEstadisticaJugador';

    protected $fillable = [
        'idJugador',
        'idTorneo',
        'goles',
        'asistencias',
        'tarjetasAmarillas',
        'tarjetasRojas',
        'minutosJugados',
        'partidosJugados',
    ];

    protected $casts = [
        'goles' => 'integer',
        'asistencias' => 'integer',
        'tarjetasAmarillas' => 'integer',
        'tarjetasRojas' => 'integer',
        'minutosJugados' => 'integer',
        'partidosJugados' => 'integer',
    ];

    public function jugador(): BelongsTo
    {
        return $this->belongsTo(Jugador::class, 'idJugador', 'idJugador');
    }

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class, 'idTorneo', 'idTorneo');
    }
}
