<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Turno;
use App\TurnoHelper;
class TurnoUnico implements ValidationRule
{
    protected $doctor_id;
    protected $hora;
    public function __construct($doctor_id, $hora)
    {
        $this->doctor_id = $doctor_id;
        $this->hora = $hora;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (TurnoHelper::existeTurno($value, $this->doctor_id, $this->hora)) {
            $fail('Ya existe un turno para el doctor en esa fecha y hora.');
        }
    }
}
