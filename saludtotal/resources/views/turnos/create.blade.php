<x-app-layout>
    <!-- Form para pedir turno-->
    <section class ="bg-grey-lighter m-4">
        <article class="mb-2 border-solid border-grey-light rounded border shadow-sm w-full ">
            <header class="bg-gray-300 px-2 py-3 border-solid border-gray-400 border-b">
                <h1>Pedir Turno</h1>
            </header>
            <main class="p-3">
                <form id="form-pedir-turno" data-pacienteId="{{ json_encode(auth()->user()->paciente_id) }}" class="w-full">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <section id="form-pedir-turno">

                    </section>
                </form>
            </main>
        </article>
    </section>
    @section('scripts')
        @vite('resources/js/reactApp.jsx')
    @endsection
</x-app-layout>
{{--
<div class="flex flex-wrap justify-center" onclick="selectEspeciality(event)">
                        <div data-modal='centeredFormModal'
                            class="modal-trigger bg-white border-2 border-blue-900 rounded-lg shadow p-2 flex flex-col items-center w-40 mx-3
                                    hover:bg-blue-200 cursor-pointer transition duration-300"
                            data-especiality="cardiologia">
                            <img src="{{ asset('img/doctor-icon.svg') }}" alt="icon doctor"
                                class="w-16 h-16 md:w-14 md:h-14 sm:w-12 sm:h-12">
                            <p class="mt-2 text-center text-lg md:text-base sm:text-sm">Cardiologia</p>
                        </div>
                        <div data-modal='centeredFormModal'
                            class="modal-trigger bg-white border-2 border-blue-900 rounded-lg shadow p-2 flex flex-col items-center w-40 mx-3
                                    hover:bg-blue-200 cursor-pointer transition duration-300"
                            data-especiality="cardiologia">
                            <img src="{{ asset('img/doctor-icon.svg') }}" alt="icon doctor"
                                class="w-16 h-16 md:w-14 md:h-14 sm:w-12 sm:h-12">
                            <p class="mt-2 text-center text-lg md:text-base sm:text-sm">Cardiologia</p>
                        </div>
                        <div data-modal='centeredFormModal'
                            class="modal-trigger bg-white border-2 border-blue-900 rounded-lg shadow p-2 flex flex-col items-center w-40 mx-3
                                    hover:bg-blue-200 cursor-pointer transition duration-300"
                            data-especiality="cardiologia">
                            <img src="{{ asset('img/doctor-icon.svg') }}" alt="icon doctor"
                                class="w-16 h-16 md:w-14 md:h-14 sm:w-12 sm:h-12">
                            <p class="mt-2 text-center text-lg md:text-base sm:text-sm">Cardiologia</p>
                        </div>
                        <div data-modal='centeredFormModal'
                            class="modal-trigger bg-white border-2 border-blue-900 rounded-lg shadow p-2 flex flex-col items-center w-40 mx-3
                                    hover:bg-blue-200 cursor-pointer transition duration-300"
                            data-especiality="cardiologia">
                            <img src="{{ asset('img/doctor-icon.svg') }}" alt="icon doctor"
                                class="w-16 h-16 md:w-14 md:h-14 sm:w-12 sm:h-12">
                            <p class="mt-2 text-center text-lg md:text-base sm:text-sm">CLinica General</p>
                        </div>
                    </div>

                    <!-- doctor input -->
                    <div class="mb-6 w-1/2 ">
                        <div class="">

                        </div>
                        <div class="w-full">
                            <label class="block text-grey-darkest font-bold  mb-1 md:mb-0 pr-4"
                            for="doctor_id">
                                Doctor
                            </label>
                            <input class="bg-grey-200 appearance-none border-1 border-grey-200 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple-light"
                            id="doctor" name="doctor_id"
                            type="number"
                            placeholder="Nombre del Doctor">
                        </div>
                        @error('doctor_id')
                            <div class="bg-red-300 mb-2 border border-red-300 text-red-dark px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Error!</strong><span class="block sm:inline">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <!-- fecha input -->
                    <div class="mb-6 w-1/2 ">
                        <div class="">
                            <label class="block text-grey-darkest font-bold  mb-1 md:mb-0 pr-4"
                            for="fecha">
                                Fecha
                            </label>
                        </div>
                        <div class="w-full">
                            <input class="bg-grey-200 appearance-none border-1 border-grey-200 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple-light"
                            id="fecha" name="fecha"
                            type="date"
                            placeholder="Seleccione una fecha">
                            @error('fecha')
                                <div class="bg-red-300 mb-2 border border-red-300 text-red-dark px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">Error! </strong><span class="block sm:inline">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- hora input -->
                    <div class="mb-6 w-1/2 ">
                        <div class="">
                            <label class="block text-grey-darkest font-bold  mb-1 md:mb-0 pr-4"
                            for="hora">
                                Hora
                            </label>
                        </div>
                        <div class="w-full">
                            <input class="bg-grey-200 appearance-none border-1 border-grey-200 rounded w-full py-2 px-4 text-grey-darker leading-tight focus:outline-none focus:bg-white focus:border-purple-light"
                            id="hora" name="hora"
                            type="time">
                            @error('fecha')
                                <div class="bg-red-300 mb-2 border border-red-300 text-red-dark px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">Error!    </strong><span class="block sm:inline">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- submit button -->
                    <div class="">
                        <div class="md:w-1/3"></div>
                        <div class="md:w-2/3">
                            <button class="bg-green-500 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-full">
                                    Pedir Turno
                            </button>
                        </div>
                    </div> --}}
