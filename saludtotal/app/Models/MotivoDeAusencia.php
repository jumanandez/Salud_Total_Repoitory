<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoDeAusencia extends Model
{
    protected $table = 'motivos_de_ausencia';
    protected $primaryKey = 'motivo_id';
    protected $fillable = [
        'nombre',
        'descripcion'
    ];
    /**
     * Get all of the ausencias for the MotivoDeAusencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ausencias(): HasMany
    {
        return $this->hasMany(AusenciasDoctor::class,'motivo_id', 'motivo_id');
    }
}
