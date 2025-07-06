<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class HorarioDisponible extends Model
{
    protected $table = 'disponibilidades_doctores';
    protected $primaryKey = 'disponibilidad_id';
    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'duracion_minutos' => 'integer',
    ];

    /**
     * Get the doctor that owns the HorarioDisponible
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(doctor::class, 'doctor_id');
    }
}
