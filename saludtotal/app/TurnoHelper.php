<?php

namespace App;
use App\Models\Turno;
class TurnoHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function existeTurno($fecha, $doctorId, $hora){
        return $turnoExistente = Turno::
            where('doctor_id', $doctorId)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->exists();
    }
}
