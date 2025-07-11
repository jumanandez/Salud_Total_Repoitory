<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
class AusenciasDoctor extends Model
{
    protected $table = 'ausencias_doctores';
    protected $fillable = ['doctor_id', 'fecha_inicio', 'fecha_fin', 'motivo_id'];

    // Relación con el modelo Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
