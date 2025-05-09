<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Especialidad extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'especialidad_id';

    /**
     * Get all of the doctores for the Especialidad
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function doctores(): HasMany
    {
        return $this->hasMany(Doctor::class, 'especialidad', 'especialidad_id');
    }
}
