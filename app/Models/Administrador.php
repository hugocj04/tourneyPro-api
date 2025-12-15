<?php

namespace App\Models;

use App\Models\Notificacion;
use App\Models\Torneo;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Administrador extends Model
{
    protected $table = 'administradores';

    protected $primaryKey = 'idAdmin';

    protected $fillable = [
        'telefonoContacto',
        'organizacion',
        'idUsuario',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function torneos(): HasMany
    {
        return $this->hasMany(Torneo::class, 'idAdmin', 'idAdmin');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'idAdmin', 'idAdmin');
    }
}
