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
    protected $table = 'turnos';
    protected $primaryKey = 'turno_id';
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha',
        'hora',
        'estado',
        'fecha_solicitud_cancelacion',
        'solicita_cancelacion',
        'cancelado_por',
        'fecha_cancelacion',
        'fecha_solicitud_reprogramacion',
        'solicita_reprogramacion',
        'reprogramado_por',
        'fecha_reprogramacion',
        'reprogramado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_solicitud_cancelacion' => 'date',
        'fecha_solicitud_reprogramacion' => 'date',
        'fecha_reprogramacion' => 'date',
        'fecha_cancelacion' => 'date',
        'fecha_edicion' => 'date',
        'fecha_creacion' => 'date',
        'solicita_reprogramacion' => 'boolean',
        'solicita_cancelacion' => 'boolean',
        'reprogramado' => 'boolean',
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
            'especialidad_id'        // local key en `doctors` que apunta a especialidad_id
        );
    }
}
