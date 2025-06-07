<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Turno extends Model
{
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;
    protected $table = 'turnos';
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha',
        'hora',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
    /**
     * Get the doctor associated with the Turno
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function doctor(): HasOne
    {
        return $this->hasOne(doctor::class, 'doctor_id');
    }

}
