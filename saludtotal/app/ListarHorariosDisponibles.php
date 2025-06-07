<?php
namespace App;
use App\Models\Turno;
use App\TurnoHelper;
class ListarHorariosDisponibles
{
    public static  function listarHorariosDisponibles($hora_inicio,$hora_fin, $fecha, $doctor_id, $duracion_slot): array
    {
        $horario = $hora_inicio;
        $slots = [];
        while ($horario->lessThan($hora_fin)) {
            $slots[] = $horario->copy()->format('H:i');
            $horario->addMinutes($duracion_slot);
        }

        foreach ($slots as $key => $slot) {
            if (TurnoHelper::existeTurno($fecha, $doctor_id, $slot)) {
                unset($slots[$key]);
            }
        }
        $slots = array_values($slots); // Reindexar el array después de eliminar elementos
        return $slots;
    }
}
