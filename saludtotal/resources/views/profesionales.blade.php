<x-app-layout>
    <div class="flex flex-col">
        <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
            <!-- card -->
            <div class="rounded overflow-hidden bg-white px-12 w-full">
                <div class="px-6 py-2 flex justify-center ">
                    <div class="font-medium text-3xl">Profesionales</div>
                </div>

                <div id="toggle-especialidad" class="space-y-2  mt-4">
                    <!-- React component se renderiza acá -->
                </div>

                <!-- Detalle de doctor -->
                <div class="flex flex-row justify-between items-center p-6 rounded shadow-lg bg-white">
                    <!-- Primera columna: imagen + info corta -->
                    <div class="flex flex-row items-center space-x-4">
                        <!-- Imagen del doctor -->
                        <img src="{{ asset('img/doctor.png') }}" alt="Imagen doctor"
                            class="w-32 h-32 object-contain">

                        <!-- Info del doctor -->
                        <div class="text-left">
                            <p class="font-semibold">Fernando Fierro</p>
                            <p>Age: 45</p>
                            <p>Lic. en Cardiología</p>
                        </div>
                    </div>

                    <!-- Segunda columna: días, horarios y disponibilidad -->
                    <div class="flex flex-col items-start space-y-2">
                        <p class="font-semibold">Días de atención</p>
                        <p>Lunes a Jueves de 8:30 a 19:30</p>
                        <p>Viernes y sábado de 9 a 18hs</p>

                        <span class="bg-green-400 text-green-900 font-semibold px-4 py-1 rounded-full text-sm mt-2">
                            Disponible esta semana
                        </span>
                    </div>
                </div>


                <div class="flex justify-end mt-4 p-3">
                    <button class="bg-green-700 text-white px-6 py-2 rounded">Siguiente</button>
                </div>
            </div>
            <!-- /card -->
        </div>
    </div>
    @section('scripts')
        @vite('resources/js/reactApp.jsx')
    @endsection

</x-app-layout>
