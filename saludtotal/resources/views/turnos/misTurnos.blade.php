<x-app-layout>
    <div class="min-h-screen w-full bg-cover bg-center"
        style="background-image: url('{{ asset('img/Mis-turnos-background.jpg') }}');">
        <div class="flex flex-col items-center mt-8">
            <div class="p-8 w-full max-w-3xl">
                <div class="flex justify-center mb-6">
                    <h1 class="text-3xl font-bold bg-white rounded-lg px-8 py-2 shadow">
                        Mis Turnos
                    </h1>
                </div>
                <form method="GET" action="{{ route('mis-turnos') }}" class="mb-4">
                    <div class="flex justify-between  bg-white rounded-lg shadow-sm p-3">
                        <div class="w-full flex flex-row">
                            <label for="estado" class="block mb-1 mx-3 text-lg font-medium text-gray-700">Filtrar por:
                            </label>
                            <select name="estado" id="estado" class="rounded bg-gray-300/60 text-lg mx-3"
                                onchange="this.form.submit()">
                                <option value="">Estado</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo
                                </option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>
                                    Cancelado
                                </option>
                                <option value="atendido" {{ request('estado') == 'atendido' ? 'selected' : '' }}>
                                    Atendido
                                </option>
                            </select>
                        </div>
                        <div class="w-full flex flex-row justify-end">
                            <div>
                                <label for="orden" class="text-lg">Orden:</label>
                                <select name="orden" id="orden" class="rounded bg-gray-300/60 text-lg mx-3"
                                    onchange="this.form.submit()">
                                    <option value="desc" {{ request('orden') == 'desc' ? 'selected' : '' }}>Más
                                        recientes primero</option>
                                    <option value="asc" {{ request('orden') == 'asc' ? 'selected' : '' }}>Más
                                        antiguos primero</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('turnos.create') }}"
                        class="bg-white shadow rounded-lg px-5 py-3 hover:bg-blue-50 transition">
                        <i class="fa-sharp fa-solid fa-plus text-2xl text-red-700"></i>
                        <span class="font-semibold text-xl align-center text-blue-800"> Pedir Turno</span>
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach ($turnos as $turno)
                        <x-item-turno :turno="$turno" />
                    @endforeach

                    <div class="mt-4 flex justify-center">
                        {{ $turnos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
