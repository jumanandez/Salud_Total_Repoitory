<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\HorarioDisponible;
class HorarioValido implements ValidationRule
{
    protected $doctor_id;
    protected $fecha;
    public function __construct($doctor_id, $fecha) {
        $this->doctor_id = $doctor_id;
        $this->fecha = $fecha;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $diaDeLaSemanaPedido = date('N', strtotime($this->fecha));

        $horarioDisponible = HorarioDisponible::
            where('doctor_id', $this->doctor_id)
            ->where('dia_semana', $diaDeLaSemanaPedido)
            ->where('hora_inicio', '<=', $value)
            ->where('hora_fin', '>=', $value)
            ->exists();

        if (!$horarioDisponible) {
            $fail('El horario no está disponible para el doctor en esa fecha.');
        }
    }
}
