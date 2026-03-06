<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoPartido extends Model
{
    protected $table = 'evento_partidos';
    
    protected $primaryKey = 'idEvento';

    public static $snakeAttributes = false;
    
    protected $fillable = [
        'idPartido',
        'idJugador',
        'idEquipo',
        'tipoEvento',
        'minuto',
        'descripcion',
    ];
    
    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class, 'idPartido', 'idPartido');
    }
    
    public function jugador(): BelongsTo
    {
        return $this->belongsTo(Jugador::class, 'idJugador', 'idJugador');
    }
    
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipo', 'idEquipo');
    }
}
