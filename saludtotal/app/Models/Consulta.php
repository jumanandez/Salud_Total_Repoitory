<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $primaryKey = 'consulta_id';
    public $timestamps = false;
    protected $fillable = [
        'paciente_id',
        'nombre_apellido',
        'email',
        'telefono',
        'mensaje',
    ];
}
