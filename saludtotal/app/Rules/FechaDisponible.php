<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\HorarioDisponible;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class FechaDisponible implements ValidationRule
{
    protected $doctor_id;

    public function __construct($doctor_id)
    {
        $this->doctor_id = $doctor_id;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!strtotime($value)) {
    $fail('La fecha proporcionada no es válida.');
    return;
}

        $diaDeLaSemanaPedido = Carbon::parse($value, 'America/Argentina/Buenos_Aires')->dayOfWeekIso;

        $existeEnDisponibilidades = HorarioDisponible::
            where('doctor_id', $this->doctor_id)
            ->where('dia_semana', $diaDeLaSemanaPedido)
            ->exists();
        $doctor = $this->doctor_id;
        if (!$existeEnDisponibilidades) {
            $fail('El doctor no atiende el día seleccionado.');
        }

        $existeAusencia = DB::table('ausencias_doctores')
            ->where('doctor_id', $this->doctor_id)
            ->whereDate('fecha_inicio', '<=', $value)
            ->whereDate('fecha_fin', '>=', $value)
            ->exists();

        if ($existeAusencia) {
            $fail('El Doctor no se encuentra disponible en la fecha seleccionada.');
        }

    }
}
