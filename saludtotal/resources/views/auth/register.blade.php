<x-guest-layout>
    <div class="w-full lg:max-w-2xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
            style="background-color: rgba(255, 255, 255, 0.6);">
    <div class="flex flex-row justify-center items-center">
    <form class="" method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div>
            <x-input-label for="nombre_apellido" :value="__('Nombre y Apellido')" />
            <x-text-input id="nombre_apellido" class="block  w-full" type="text" name="nombre_apellido" :value="old('nombre_apellido')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('nombre_apellido')" class="mt-2" />
        </div>

        <!-- DNI -->
        <div class="mt-4">
            <x-input-label for="dni" :value="__('DNI')" />
            <x-text-input id="dni" class="block  w-full" type="text" name="dni" :value="old('dni')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block  w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- telefono -->
        <div class="mt-4">
            <x-input-label for="telefono" :value="__('Telefono')" />
            <x-text-input id="telefono" class="block  w-full" type="text"
                            name="telefono" :value="old('telefono')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block  w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />

            <x-text-input id="password_confirmation" class="block  w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Ya tiene una cuenta?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
        </div>
    </form>
    <div class="flex flex-col align-content-start pl-6">
        <div class="mb-4">
            <img src="{{asset('img/Salud_total_black.svg')}}" alt="salud total logo" class="w-32">
        </div>
        <img src="{{ asset('img/register-img.jpg') }}" alt="salud total logo" class="w-full">
    </div>

    </div>
    </div>
</x-guest-layout>
