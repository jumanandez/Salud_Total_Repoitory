<div class="flex items-center justify-between rounded-xl shadow-md px-6 py-4 transition-transform hover:scale-[1.02] hover:shadow-xl bg-white mb-2">
    <div class="flex flex-col">
        <span class="text-base font-semibold">
            Turno con {{ $turno->doctor->nombre_apellido ?? 'Doctor desconocido' }}
            <span class="font-normal text-blue-500">({{ $turno->especialidad->nombre ?? 'Especialidad desconocida' }})</span>
            <span class="font-normal text-gray-400">#ID: {{ $turno->turno_id }}</span>
        </span>
        <span class="text-sm text-gray-900 mt-1">
            {{ \Carbon\Carbon::parse($turno->fecha)->format('d-m-Y') }} - {{ $turno->hora }} {{ "({$turno->estado})" }}
        </span>
    </div>
    <a
        href="{{ route('turnos.details', ['turno_id' => $turno->turno_id]) }}"
        class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-400 shadow-md hover:bg-yellow-500 transition-colors duration-200 modal-trigger"
        title="Editar"
        data-modal="centeredFormModal"
    >
        <i class="fa-solid fa-edit text-white"></i>
    </a>
</div>
