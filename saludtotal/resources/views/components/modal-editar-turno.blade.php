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
            <form>
                <label class="block uppercase tracking-wide text-gray-600 text-xs font-light mb-1" for="fecha-reprogramacion">
                    Nueva Fecha
                </label>
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-800 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-yellow-400 mb-6"
                    id="fecha-reprogramacion"
                    name="fecha_reprogramacion"
                    type="text"
                    placeholder="Selecciona una fecha"
                />
                <div class="flex flex-wrap gap-3 justify-end mt-2">
                    <button type="submit" class='bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition-colors'>
                        Reprogramar Turno
                    </button>
                    <button type="button" class='bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded transition-colors'>
                        Cancelar Turno
                    </button>
                    <button type="button" class='close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition-colors'>
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>