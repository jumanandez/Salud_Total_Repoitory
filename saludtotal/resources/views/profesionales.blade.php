<x-app-layout>
    <div class="flex flex-col">
        <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
            <!-- card -->
            <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                <div class="px-6 py-2 border-b border-light-grey flex justify-center">
                    <div class="font-medium text-3xl">Profesionales</div>
                </div>

                <div id="especialidades" class="space-y-2">
                    {{-- @foreach($especialidades as $especialidad)
                        <div class="border rounded overflow-hidden">
                            <button class="w-full bg-grey-dark text-left px-4 py-2 flex justify-between items-center" onclick="toggleSection(this)">
                                <span class="text-light text-3xl">{{ $especialidad->nombre }}</span>
                                <span class="toggle-icon text-blue-600 text-xl">+</span>
                            </button>
                            <div class="hidden bg-gray-100 p-2 space-y-1">
                                @foreach($especialidad->doctores as $profesional)
                                    <div class="cursor-pointer px-3 py-1 bg-blue-100 rounded hover:bg-blue-200" onclick="selectDoctor(this)">
                                        {{ $profesional->nombre_apellido }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @endforeach --}}
                </div>

                <div class="mt-4 p-4 border rounded text-center" id="selected-profesional">
                    <p class="text-gray-500">Seleccione un profesional</p>
                </div>

                <div class="flex justify-end mt-4 p-3">
                    <button class="bg-green-700 text-white px-6 py-2 rounded">Siguiente</button>
                </div>
            </div>
        <!-- /card -->
        </div>
    </div>
@section('scripts')
    <script type="module" src="{{ asset('js/modules/especialidades.js') }}"></script>
@endsection

</x-app-layout>
