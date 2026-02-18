<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscripcionEquipo extends Model
{
    protected $table = 'inscripcion_equipos';
    
    protected $primaryKey = 'idInscripcion';
    
    protected $fillable = [
        'idTorneo',
        'idEquipo',
        'fechaInscripcion',
        'estado',
        'montoAbonado',
    ];
    
    protected $casts = [
        'fechaInscripcion' => 'datetime',
        'montoAbonado' => 'decimal:2',
    ];
    
    // Relaciones
    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class, 'idTorneo', 'idTorneo');
    }
    
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'idEquipo', 'idEquipo');
    }
}
