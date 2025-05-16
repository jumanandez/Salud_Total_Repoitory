<x-app-layout>
    <div class="flex flex-col">
        <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
            <!-- main content -->
            <div class="rounded overflow-hidden bg-white px-12 w-full">
                <div class="px-6 py-2 flex justify-center ">
                    <div class="font-medium text-3xl">Profesionales</div>
                </div>

                <div id="toggle-especialidad" class="space-y-2  mt-4">
                    <!-- React component se renderiza acá -->
                </div>
            </div>
            <!-- /main content -->
        </div>
    </div>
    @section('scripts')
        @vite('resources/js/reactApp.jsx')
    @endsection

</x-app-layout>
