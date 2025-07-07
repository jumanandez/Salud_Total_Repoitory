<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TurnoUnico;
use App\Rules\HorarioValido;
use App\Rules\FechaDisponible;

class StoreTurnoRequest extends FormRequest
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
        $request = $this->request;
        return [
            'paciente_id' => 'bail|sometimes|exists:pacientes,paciente_id',
            'doctor_id' => 'bail|required|exists:doctores,doctor_id',
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
            'paciente_id.exists' => 'El paciente seleccionado no existe.',
            'doctor_id.required' => 'El campo doctor es obligatorio.',
            'doctor_id.exists' => 'El doctor seleccionado no existe.',
            'fecha.required' => 'El campo fecha es obligatorio.',
            'fecha.date' => 'El campo fecha debe ser una fecha válida.',
            'fecha.date_format' => 'El campo fecha debe tener el formato Y-m-d. ej:' . now()->format('Y-m-d'),
            'fecha.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'hora.required' => 'El campo hora es obligatorio.',
        ];
    }
}
