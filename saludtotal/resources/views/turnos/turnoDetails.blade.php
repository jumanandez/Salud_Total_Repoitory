<x-app-layout>
    <div class="flex justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl w-full">
        <h1 class="bg-gray-400 text-3xl font-semibold mb-6 text-center border-b-2 shadow">
            Detalle del Turno
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-lg">
            <x-turno.info label="Id del Turno:" valor="{{ $turno->turno_id }}">
                <i class="fa-solid fa-hashtag text-xl text-gray-700"></i>
            </x-turno.info>
            <x-turno.info label="Fecha del Turno:" valor="{{ $turno->fecha->format('d/m/Y') }}">
                <i class="fa-solid fa-calendar-days text-xl text-gray-700"></i>
            </x-turno.info>
            <x-turno.info label="Hora:" valor="{{ $turno->hora . ' hs' }}">
                <i class="fa-solid fa-clock text-xl text-gray-700"></i>
            </x-turno.info>

            <x-turno.estado label="Estado:" valor="{{ $turno->estado }}">
            </x-turno.estado>
            <x-turno.info label="Doctor:" valor="{{ $turno->doctor->nombre_apellido }}">
                <i class="fa-solid fa-user text-xl text-blue-900"></i>
            </x-turno.info>
            <x-turno.info label="Creado el:" valor="{{ $turno->created_at->format('d/m/Y') }}">
                <i class="fa-solid fa-calendar-days text-xl text-gray-700"></i>
            </x-turno.info>
            @if($turno->updated_at)
            <x-turno.info label="Última edición:" valor="{{ $turno->updated_at->format('d/m/Y') ?? 'Sin ediciones' }}">
                <i class="fa-solid fa-pen-to-square text-xl text-gray-{{$turno->updated_at ? '700' : '500'}} hover:text-gray-700"></i>
            </x-turno.info>
            @endif
            <x-turno.info label="¿Reprogramado?:" valor="{{ $turno->reprogramado ? 'Sí' : 'No' }}">
                <i class="fa-solid fa-circle-{{ $turno->reprogramado ? 'check' : 'xmark' }} text-xl text-gray-{{$turno->reprogramado ? '700' : '500'}} hover:text-gray-700"></i>
            </x-turno.info>
            <x-turno.info label="¿Solicitó reprogramación?:" valor="{{ $turno->solicita_reprogramacion ? 'Sí' : 'No' }}">
                <i class="fa-solid fa-circle-{{ $turno->solicita_reprogramacion ? 'check' : 'xmark' }} text-xl text-gray-{{$turno->solicita_reprogramacion ? '700' : '500'}} hover:text-gray-700"></i>
            </x-turno.info>
            @if($turno->fecha_solicitud_reprogramacion)
                <x-turno.info label="Fecha de la solicitud de reprogramación:" valor="{{ $turno->fecha_solicitud_reprogramacion->format('d/m/Y') }}">
                    <i class="fa-solid fa-calendar-days text-xl text-gray-700"></i>
                </x-turno.info>
            @endif

            @if($turno->reprogramado)
                <x-turno.info label="Reprogramado por (ID):" valor="{{ $turno->reprogramado_por }}">
                    <i class="fa-solid fa-user text-xl text-gray-700"></i>
                </x-turno.info>
            @endif

            <x-turno.info label="¿Solicitó cancelación?:" valor="{{ $turno->solicita_cancelacion ? 'Sí' : 'No' }}">
                <i class="fa-solid fa-circle-{{ $turno->solicita_cancelacion ? 'check' : 'xmark' }} text-xl text-gray-{{$turno->solicita_cancelacion ? '700' : '500'}} hover:text-gray-700"></i>
            </x-turno.info>
            @if($turno->fecha_solicitud_cancelacion && $turno->cancelado_por)
                <x-turno.info label="Fecha solicitud cancelación:" valor="{{ $turno->fecha_solicitud_cancelacion->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i') . 'hs' }}">
                    <i class="fa-solid fa-calendar-days text-xl text-gray-700"></i>
                </x-turno.info>

                <x-turno.info label="Cancelado por:" valor="Tú">
                    <i class="fa-solid fa-user text-xl text-gray-700"></i>
                </x-turno.info>
            @endif
            @if ($turno->canceled_at && !$turno->cancelado_por)
                    <x-turno.info label="Fecha de cancelación:" valor="{{ $turno->canceled_at->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i') }}">
                        <i class="fa-solid fa-calendar-days text-xl text-gray-700"></i>
                    </x-turno.info>
                    <x-turno.info label="Cancelado por:" valor="Profesional">
                    <i class="fa-solid fa-user text-xl text-gray-700"></i>
                </x-turno.info>
            @endif
        </div>

        <div class="mt-8 flex flex-col md:flex-row gap-4 justify-center">
            {{-- Botón Reprogramación --}}
                <button id="boton-reprogramar" data-modal='centeredFormModal'
                    class="modal-trigger w-full md:w-auto font-semibold px-6 py-2 rounded-lg transition
                        {{ $turno->solicita_reprogramacion || $turno->solicita_cancelacion ? 'bg-yellow-300 cursor-not-allowed' : 'bg-yellow-500 hover:bg-yellow-600 text-white' }}"
                    {{ $turno->solicita_reprogramacion || $turno->solicita_cancelacion ? 'disabled title=Ya+solicitado+o+cancelado' : '' }}>
                    Solicitar Reprogramación
                </button>

            {{-- Botón Cancelación --}}
            <form id="form-cancelarTurno" data-turno_id='{{ json_encode($turno->turno_id) }}'>
                @method('PUT')
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <button type="submit" class="w-full md:w-auto font-semibold px-6 py-2 rounded-lg transition
                        {{ $turno->solicita_cancelacion || $turno->cancelado_por ? 'bg-red-300 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600 text-white' }}"
                    {{$turno->estado == 'cancelado' || $turno->solicita_cancelacion || $turno->cancelado_por ? 'disabled title=Ya+solicitado+o+cancelado' : '' }}>
                    Solicitar Cancelación
                </button>
            </form>

        </div>
    </div>
</div>
<x-modal-editar-turno :turno="$turno"/>
@section('scripts')
<script src="{{asset('js/formCancelarTurno.js')}}"></script>
<script src="{{asset('js/fiilSelectHorarios.js')}}"></script>
@endsection
</x-app-layout>


