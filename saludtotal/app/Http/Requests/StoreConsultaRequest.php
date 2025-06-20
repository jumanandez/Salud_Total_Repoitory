<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultaRequest extends FormRequest
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
            'nombre_apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'telefono' => 'required|string|max:255',
            'mensaje' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return
        [
            'nombre_apellido.string' => 'El nombre debe ser una cadena de caracteres',
            'nombre_apellido.max' => 'El nombre no puede tener mas de 255 caracteres',
            'nombre_apellido.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.string' => 'El email debe ser una cadena de caracteres',
            'email.email' => 'El email no es valido',
            'email.max' => 'El email no puede tener mas de 255 caracteres',
            'telefono.required' => 'El telefono es obligatorio',
            'telefono.string' => 'El telefono debe ser una cadena de caracteres',
            'telefono.max' => 'El telefono no puede tener mas de 255 caracteres',
            'mensaje.required' => 'El mensaje es obligatorio',
            'mensaje.string' => 'El mensaje debe ser una cadena de caracteres',
            'mensaje.max' => 'El mensaje no puede tener mas de 255 caracteres',
        ];
    }
}
