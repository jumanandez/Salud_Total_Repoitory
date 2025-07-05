<div id='centeredFormModal' class="modal-wrapper">
    <div class="overlay close-modal"></div>
    <div class="modal modal-centered">
        <div class="modal-content shadow-lg p-5">
            <div class="border-b p-2 pb-3 pt-0 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold">Editar Turno</span>
                    <span class='close-modal cursor-pointer px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200'>
                        <i class="fas fa-times text-gray-700"></i>
                    </span>
                </div>
            </div>
            <form id="form-reprogramar-turno"
            data-turno="{{ json_encode($turno) }}"
            data-doctor_id="{{ $turno->doctor_id }}"
            data-fecha="{{ $turno->fecha }}"
            >
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <label id="label-fecha" class="block uppercase tracking-wide text-gray-800 text-xs font-regular mb-1" for="nueva_fecha">
                    Fecha
                </label>
                <input
                    class="block w-full bg-gray-100 text-gray-800 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-yellow-400 mb-6"
                    id="input_fecha"
                    name="nueva_fecha"
                    type="date"
                    placeholder="Selecciona una fecha"
                    value="{{ $turno->fecha->format('Y-m-d') }}"
                />
                <label class="block uppercase tracking-wide text-gray-800 text-xs font-regular mb-1" for="nueva_fecha">
                    Hora
                </label>
                <select
                    class="block w-full bg-gray-100 text-gray-800 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-yellow-400 mb-6"
                    id="select_nueva_hora"
                    name="nueva_hora"
                    type="select"
                    value="{{ $turno->hora }}"
                >
                </select>
                <div id="resultado-reprogramacion" class="hidden bg-green-300 text-green-900 rounded-full px-2 mt-2 text-center">

                </div>
                <div class="flex flex-wrap gap-3 justify-between mt-2">
                    <button type="submit" class='bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition-colors'>
                        Reprogramar Turno
                    </button>
                    <button type="button" class='close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition-colors'>
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
