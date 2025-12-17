<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secuencia extends Model
{
    protected $table = 'secuencias';
    protected $primaryKey = 'idSecuencia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'valor',
    ];

    protected $casts = [
        'valor' => 'integer',
    ];
}