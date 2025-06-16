<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Especialidad;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        return $this->belongsTo(User::class, 'paciente_id');
    }
    /**
     * Get the doctor associated with the Turno
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function especialidad(): HasOneThrough
{
    return $this->hasOneThrough(
        Especialidad::class,  // modelo final
        Doctor::class,        // modelo intermedio
        'doctor_id',          // foreign key en `turnos` que apunta a `doctors.id`
        'especialidad_id',    // foreign key en `doctors` que apunta a `especialidades.especialidad_id`
        'doctor_id',          // local key en `turnos`
        'especialidad'        // local key en `doctors` que apunta a especialidad_id
    );
}
}
