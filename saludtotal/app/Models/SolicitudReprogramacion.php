<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudReprogramacion extends Model
{
    protected $table = 'solicitud_reprogramacion';
    protected $primaryKey = 'solicitud_id';
    protected $fillable = [
        'turno_id',
        'fecha',
        'hora',
        'estado',
    ];
    protected $casts = [
        'turno_id' => 'integer',
        'fecha' => 'date',
    ];
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'turno_id');
    }
}
