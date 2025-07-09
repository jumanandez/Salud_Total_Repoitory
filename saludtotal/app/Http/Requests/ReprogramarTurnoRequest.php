<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FechaDisponible;
use App\Rules\HorarioValido;
use App\Rules\TurnoUnico;

class ReprogramarTurnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha' => ['bail','required',
                        'date',
                        'date_format:Y-m-d',
                        'after_or_equal:today',
                        new TurnoUnico($this->doctor_id, $this->hora),
                        new FechaDisponible($this->doctor_id)],
            'hora' => ['required',new HorarioValido($this->doctor_id, $this->fecha)]
        ];
    }
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'fecha.date_format' => 'El formato de la fecha debe ser YYYY-MM-DD.',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'hora.required' => 'La hora es obligatoria.',
        ];
    }
}
