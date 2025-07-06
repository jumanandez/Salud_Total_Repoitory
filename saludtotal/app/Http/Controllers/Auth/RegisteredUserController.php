<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre_apellido' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:255', 'unique:'.User::class,'regex:/^\d+$/', 'digits_between:8,9'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telefono' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],
        [
            'nombre_apellido.required' => 'El campo nombre apellido es obligatorio.',
            'nombre_apellido.string' => 'El campo nombre apellido debe ser una cadena de caracteres.',
            'nombre_apellido.max' => 'El campo nombre apellido debe tener no mas de 255 caracteres.',
            'dni.required' => 'El campo dni es obligatorio.',
            'dni.string' => 'El campo dni debe ser una cadena de caracteres.',
            'dni.max' => 'El campo dni debe tener no mas de 255 caracteres.',
            'dni.unique' => 'El dni ingresado ya existe.',
            'dni.regex' => 'El DNI solo puede contener números.',
            'dni.digits_between' => 'El DNI no es válido.',
            'email.required' => 'El campo email es obligatorio.',
            'email.string' => 'El campo email debe ser una cadena de caracteres.',
            'email.lowercase' => 'El campo email debe ser en minúsculas.',
            'email.email' => 'El campo email debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo email debe tener no mas de 255 caracteres.',
            'email.unique' => 'El email ingresado ya existe.',
            'telefono.required' => 'El campo telefono es obligatorio.',
            'telefono.string' => 'El campo telefono debe ser una cadena de caracteres.',
            'telefono.max' => 'El campo telefono debe tener no mas de 255 caracteres.',
            'password.required' => 'El campo password es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);


        $user = User::create([
            'nombre_apellido' => $request->nombre_apellido,
            'dni' => $request->dni,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('welcome', absolute: false));
    }
}
