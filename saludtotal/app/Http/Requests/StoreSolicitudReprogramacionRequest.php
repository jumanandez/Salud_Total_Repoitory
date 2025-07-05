<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FechaDisponible;

class StoreSolicitudReprogramacionRequest extends FormRequest
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
            'turno_id' => 'required|exists:turnos,turno_id',
            'nueva_fecha' =>['required',
                             'date_format:Y-m-d',
                             'after_or_equal:today',
                             new FechaDisponible($this->doctor_id)],
            'nueva_hora' => 'required|date_format:H:i',
        ];
    }
    public function messages()
    {
        return [
            'turno_id.required' => 'Debes seleccionar un turno',
            'turno_id.exists' => 'El turno seleccionado no existe',
            'nueva_fecha.required' => 'Debes seleccionar una fecha',
            'nueva_fecha.date_format' => 'La fecha debe tener el formato AAAA-MM-DD',
            'nueva_fecha.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'nueva_hora.required' => 'Debes seleccionar una hora',
            'nueva_hora.date_format' => 'La hora debe tener el formato HH:MM',
        ];
    }
}
