<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                        <x-item-turno :turno="$turno" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-modal-editar-turno />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#fecha-reprogramacion", {
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "es"
            });
        });
    </script>
</x-app-layout>
