<?php

namespace App\Models;

use App\Models\Administrador;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $primaryKey = 'idNotificacion';

    protected $fillable = [
        'titulo',
        'mensaje',
        'fechaEnvio',
        'leida',
        'idUsuario',
        'idAdmin',
    ];

    protected $casts = [
        'fechaEnvio' => 'datetime',
        'leida' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(Administrador::class, 'idAdmin', 'idAdmin');
    }
}
