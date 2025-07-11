<?php

namespace App;
use App\Models\Turno;
use App\Enum\EstadoTurno;
class TurnoHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function existeTurno($fecha, $doctorId, $hora)
    {
        return Turno::where('doctor_id', $doctorId)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->where(function ($query) {
                $query->where('estado', EstadoTurno::PENDIENTE)
                    ->orWhere('estado', EstadoTurno::ACTIVO)
                    ->orWhere('estado', EstadoTurno::ACEPTADO);
            })
            ->exists();
    }

}
