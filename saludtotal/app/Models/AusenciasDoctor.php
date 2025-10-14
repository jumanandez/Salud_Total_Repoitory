<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\MotivoDeAusencia;
class AusenciasDoctor extends Model
{
    protected $table = 'ausencias_doctores';
    protected $primaryKey = 'ausencia_id';
    protected $fillable = ['doctor_id', 'fecha_inicio', 'fecha_fin', 'motivo_id'];

    // Relación con el modelo Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    /**
     * Get the motivo associated with the AusenciasDoctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function motivo(): HasOne
    {
        return $this->hasOne(MotivoDeAusencia::class,'motivo_id', 'motivo_id');
    }
}
