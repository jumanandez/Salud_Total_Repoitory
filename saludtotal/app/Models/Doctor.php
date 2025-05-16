<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $table = 'doctores';
    protected $primaryKey = 'doctor_id';

    /**
     * Get the especialidad that owns the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, 'especialidad', 'especialidad_id');
    }
    /**
     * Get all of the horarios for the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function horarios(): HasMany
    {
        return $this->hasMany(HorarioDisponible::class, 'doctor_id');
    }
}
