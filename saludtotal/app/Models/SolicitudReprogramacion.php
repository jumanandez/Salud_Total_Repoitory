<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\EstadoSolicitudReprogramacion;
use App\Models\Turno;
class SolicitudReprogramacion extends Model
{
    protected $table = 'solicitud_reprogramacion';
    protected $primaryKey = 'id';
    protected $fillable = [
        'turno_id',
        'fecha',
        'hora',
        'estado',
    ];
    protected $casts = [
        'turno_id' => 'integer',
        'fecha' => 'date',
        'estado' => EstadoSolicitudReprogramacion::class,
    ];
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'turno_id');
    }
}
