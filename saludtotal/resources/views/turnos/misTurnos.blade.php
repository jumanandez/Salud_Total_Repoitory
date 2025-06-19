<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <div class="min-h-screen w-full bg-cover bg-center" style="background-image: url('{{ asset('img/Mis-turnos-background.jpg') }}');">
        <div class="flex flex-col items-center mt-8">
            <div class="bg-white/90 rounded-2xl shadow-lg p-8 w-full max-w-3xl">
                <div class="flex justify-center mb-6">
                    <h1 class="text-3xl font-bold bg-white rounded-lg px-8 py-2 shadow border-b-4 ">
                        Mis turnos
                    </h1>
                </div>

                <div class="space-y-4">
                    @foreach($turnos as $turno)
                        <div class="flex items-center justify-between rounded-xl shadow-md px-6 py-4 transition-transform hover:scale-[1.02] hover:shadow-xl bg-white mb-2">
                            <div class="flex flex-col">
                                <span class="text-base font-semibold">
                                    Turno con {{ $turno->doctor->nombre_apellido ?? 'Doctor desconocido' }} <span class="font-normal text-blue-500">({{ $turno->especialidad->nombre ?? 'Especialidad desconocida' }})</span>
                                </span>
                                <span class="text-sm text-gray-900 mt-1">
                                    {{ \Carbon\Carbon::parse($turno->fecha)->format('d-m-Y') }} - {{ $turno->hora }} {{ "({$turno->estado})" }}
                                </span>
                            </div>
                            <button 
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-400 shadow-md hover:bg-yellow-500 transition-colors duration-200 modal-trigger" 
                                title="Editar"
                                type="button"
                                data-modal="centeredFormModal"
                            >
                                <!-- Ícono de lápiz -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l-1.464-1.464a2 2 0 00-2.828 0l-7.071 7.071a2 2 0 000 2.828l1.464 1.464a2 2 0 002.828 0l7.071-7.071a2 2 0 000-2.828z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7l-1.5-1.5" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

<!-- Centered With a Form Modal -->
<div id='centeredFormModal' class="modal-wrapper">
    <div class="overlay close-modal"></div>
    <div class="modal modal-centered">
        <div class="modal-content shadow-lg p-5">
            <div class="border-b p-2 pb-3 pt-0 mb-4">
               <div class="flex justify-between items-center">
                    Editar Turno
                    <span class='close-modal cursor-pointer px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200'>
                        <i class="fas fa-times text-gray-700"></i>
                    </span>
               </div>
            </div>
            <label class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1" for="fecha-reprogramacion">
    Nueva Fecha
</label>
<input
    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
    id="fecha-reprogramacion"
    name="fecha_reprogramacion"
    type="text"
    placeholder="Selecciona una fecha"
>
                <div class="mt-5">
                    <button class='bg-yellow-500 hover:bg-yellow-800 text-white font-bold py-2 px-4 rounded'> Reprogramar Turno </button>
                    <button class='bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded'> Cancelar Turno </button>
                    <span class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'>
                        Cerrar Ventana
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
