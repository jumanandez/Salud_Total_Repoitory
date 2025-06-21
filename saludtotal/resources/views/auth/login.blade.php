<x-guest-layout>
    <div class="w-full sm:max-w-md mt-12 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg" style="background-color: rgba(255, 255, 255, 0.6);">
        <div class="flex justify-center items-center mb-4">
            <img src="{{asset('img/Salud_total_black.svg')}}" alt="salud total logo" class="w-40">
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4 flex justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recordar usuario') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex flex-col">
                    <div class="mt-2">
                        <span class="text-xs"> ¿No tienes cuenta?</span>
                        <a href="{{ route('register') }}" class="underline text-sm text-blue-600 hover:text-blue-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Registrate') }}
                        </a>
                    </div>
                </div>
                <x-primary-button class="ms-3">
                    {{ __('Iniciar Sesión') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
