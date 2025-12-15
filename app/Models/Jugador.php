<?php

namespace App\Models;

use App\Models\Equipo;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jugador extends Model
{
    protected $table = 'jugadores';

    protected $fillable = [
        'dorsal',
        'posicion',
        'idUsuario',
        'IdEquipo',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'IdEquipo', 'IdEquipo');
    }
}
