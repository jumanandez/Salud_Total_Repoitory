<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="tailwind,tailwindcss,tailwind css,css,starter template,free template,admin templates, admin template, admin dashboard, free tailwind templates, tailwind example">
    <!-- Css -->
    <link rel="stylesheet" href={{asset("css/styles.css")}}>
    <link rel="stylesheet" href={{asset("css/all.css")}}>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <title>{{$title ?? "Salud Total" }}</title>
</head>

<body>
<!--Container -->
<div class="mx-auto bg-grey-400">
    <!--Screen-->
    <div class="min-h-screen flex flex-col">
        <!--Header Section Starts Here-->
        <header class="bg-nav relative z-20">
            <div id="top" class="flex justify-between">

                <div class="p-1 mx-3 inline-flex items-center">
                    <i class="fa-solid fa-bars pr-2 text-white text-3xl cursor-pointer" onclick="sidebarToggle()"></i>
                </div>
                <div>

                    <div class="p-2 flex flex-row items-center gap-4">
                        @auth
                            <a href="{{ route('mis-turnos') }}"
                                class="text-white bg-blue-900 hover:bg-blue-700 p-2 rounded-lg">
                                <span class="px-5">Mis Turnos</span>
                            </a>
                        @endauth
                        <a href="{{route('turnos.create')}}" class="text-white bg-green-dark hover:bg-green-800 p-2 rounded-full">
                            <span class="px-5">Pedir Turno</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <!--/Header-->

        <div class="flex flex-1">
            <!--Sidebar-->
            <aside id="sidebar" style="display: none;" class="bg-side-nav  border-r border-side-nav">

                <ul class="list-reset flex flex-col">
                    <!-- Menu Item  -->
                    <a id="home" href="index.html"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                        <li class=" w-full h-full py-3 px-2 border-b border-light-border">
                            <div class="w-full">
                                <i class="fa-solid fa-house  mx-2"></i>
                            </div>
                        </li>
                    </a>
                    <!-- Menu Item  -->
                    <a href="{{ route('mis-turnos') }}"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                        <li class=" w-full h-full py-3 px-2 border-b border-light-border">
                            <div class="w-full">
                                <i class="fa-solid fa-calendar-days float-top mx-2"></i>
                            </div>
                        </li>
                    </a>
                </ul>
            </aside>
            <!--/Sidebar-->
            <!--Main-->
            <main class="bg-white-300 flex-1 overflow-hidden">
                <!--Shortcuts-->
                <div class="flex flex-row justify-between items-center pb-4">
                    <div class="flex flex-1">
                        <img src="{{asset('img/salud_total_black.svg')}}" alt="salud total logo" class="w-1/4">
                    </div>

                    <div class="flex flex-row gap-10 align-items-center">
                        <div class="p-6">
                            <a href="#consulta-especial">
                                <span class="text-2xl">Consultas <i class="fa-solid fa-angle-down align-bottom"></i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            <a href="#footer">
                                <span class="text-2xl">Contáctanos <i class="fa-solid fa-angle-down align-bottom"> </i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            <a href="{{route('profesionales.index')}}">
                                <span class="text-2xl">Médicos <i class="fa-solid fa-angle-down align-bottom"> </i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            <a href="#servicios-clinicos">
                                <span class="text-2xl">Servicios Clínicos <i class="fa-solid fa-angle-down align-bottom"> </i></span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--/Shortcuts-->
                <!--Content-->
                <!--img src="{asset('img/WelcomeFondo1.png')}}" alt="salud total logo" class="w-full h-1/2"-->
                <section class="relative z-0 bg-cover bg-center" style="background-image: url('{{ asset('img/WelcomeFondo1.jpg') }}')" >
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <h1 class="text-4xl font-bold text-black text-center mb-12 drop-shadow-lg">
                            Bienvenido a Salud Total
                        </h1>
                        <div class="flex flex-row space-x-9">
                            <x-panel-info img="img/panel1.jpg" title="Chequeos Médicos">
                                Realizarse un análisis de sangre de forma regular es fundamental para cuidar la salud. Este examen permite detectar de manera temprana posibles enfermedades, como la diabetes, el colesterol alto, problemas hepáticos, anemia o infecciones. Además, ayuda a monitorear el estado general del cuerpo y la efectividad de tratamientos.
                            </x-panel-info>
                            <x-panel-info img="img/panel2.png" title="La importancia de tomarse la presión">
                                Tomarse la presión arterial con un doctor permite evaluar el funcionamiento del corazón y el sistema circulatorio. Es un control simple, rápido y sin dolor, pero muy útil para detectar problemas como la hipertensión, una condición que muchas veces no presenta síntomas pero que puede causar serios daños si no se trata a tiempo.
                            </x-panel-info>
                        </div>
                    </div>
                </section>

                <section class="bg-white ">
                    <h1 class="text-2xl font-semibold text-center mb-4 mt-4">Pedí tu turno</h1>
                    <div class="flex justify-center items-center flex-row pb-4">
                        <x-turno-link href="{{ route('turnos.create') }}" img="img/doctor.svg" label="Cardiología"/>
                        <x-turno-link href="{{ route('turnos.create') }}" img="img/doctor.svg" label="Ginecología"/>
                        <x-turno-link href="{{ route('turnos.create') }}" img="img/doctor.svg" label="Pediatría"/>
                        <x-turno-link href="{{ route('turnos.create') }}" img="img/doctor.svg" label="Clínica General"/>
                    </div>
                </section>

                <section id="servicios-clinicos"
                    class="relative z-0 bg-cover bg-center py-2"
                    style="background-image: url('{{ asset('img/WelcomeFondo2.jpg') }}')" >
                    <h1 class="text-4xl font-bold text-black text-center mb-12 drop-shadow-lg">
                        NUESTROS SERVICIOS CLINICOS
                    </h1>
                    <div class="grid grid-cols-2 gap-8 max-w-6xl mx-auto">
                        <x-panel-info img="img/Cardiologia.jpg" title="Cardiología">
                            Ofrecemos un diagnóstico y tratamiento integral de las enfermedades cardiovasculares, asegurando una atención continua y de calidad.
                        </x-panel-info>
                        <x-panel-info img="img/Pediatria.jpg" title="Pediatría">
                            Cuidamos el crecimiento y desarrollo saludable de los más pequeños, especializada desde la infancia hasta la adolescencia.
                        </x-panel-info>
                        <x-panel-info img="img/Ginecologia.jpg" title="Ginecología">
                            Brindamos servicios completos de salud femenina, desde chequeos rutinarios hasta tratamientos especializados.
                        </x-panel-info>
                        <x-panel-info img="img/clinica-general.jpg" title="Clínica General">
                            Proporcionamos atención médica general con un enfoque en la prevención y la salud integral.
                        </x-panel-info>
                    </div>
                </section>

                <!-- Zona mejorada de HACER UNA CONSULTA ESPECIAL -->
                <section class="relative z-0 bg-cover bg-center py-20" style="background-image: url('{{ asset('img/Consulta.jpg') }}')">
                    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div id="consulta-especial" class="bg-white shadow-2xl rounded-lg overflow-hidden mb-8 border border-gray-200">
                            <h1 class="text-4xl font-bold text-[#0C1C3C] text-center mb-8 drop-shadow-lg pt-8">
                                HACER UNA CONSULTA ESPECIAL
                            </h1>
                                <div id="respuesta-container" class="hidden bg-green-200 text-green-800 text-center p-4 rounded-full mb-4">
                                    <p id="respuesta" class="text-lg font-semibold text-center"></p>
                                </div>
                                <div id="error-container" class="hidden bg-red-200 text-red-800 text-center p-4 rounded-full mb-4">
                                    <p id="mensaje-error" class="text-lg font-semibold text-center"></p>
                                </div>
                            <form id="consultaForm" class="p-8 space-y-6" method="POST" action="{{ route('consulta.store') }}">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div>
                                    <label for="nombre_apellido" class="block text-lg font-semibold mb-1">Nombre</label>
                                    <input type="text" id="nombre_apellido" name="nombre_apellido"
                                        class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                        placeholder="Ingrese su nombre">
                                </div>
                                <div>
                                    <label for="email" class="block text-lg font-semibold mb-1">Email</label>
                                    <input type="text" id="email" name="email"
                                        class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                        placeholder="Ingrese su email" value="{{ old('email') }}">
                                </div>
                                <div>
                                    <label for="telefono" class="block text-lg font-semibold mb-1">Teléfono</label>
                                    <input type="text" id="telefono" name="telefono"
                                        class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                        placeholder="Ingrese su teléfono" value="{{ old('telefono') }}">
                                </div>
                                <div>
                                    <label for="mensaje" class="block text-lg font-semibold mb-1">Mensaje</label>
                                    <textarea id="mensaje" name="mensaje" rows="4"
                                        class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                        placeholder="Ingrese su mensaje" value="{{ old('mensaje') }}"></textarea>
                                </div>
                                <div class="flex justify-center">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                                        Enviar Consulta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!--/Content-->

            </main>
        </div>
        <footer id="footer" style="background-color: #0D1831;" class="text-white py-8">
            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center md:items-start gap-8">
                <!-- Logo y nombre -->
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/salud_total.svg') }}" alt="Salud Total Logo" class="w-48 h-48 md:w-56 md:h-56 lg:w-64 lg:h-64">
                </div>
                <!-- Direcciones -->
                <div class="flex flex-col gap-4 p-12">
                    <x-footer-icon icon="img/map-pin.svg" alt="Dirección">Av. Díaz Colodredo 1893</x-footer-icon>
                    <x-footer-icon icon="img/map-pin.svg" alt="Dirección">Av. Neustad 134</x-footer-icon>
                    <x-footer-icon icon="img/map-pin.svg" alt="Dirección">José Gomez y Pujol</x-footer-icon>
                </div>
                <!-- Contacto -->
                <div class="flex flex-col gap-4 p-12">
                    <x-footer-icon icon="img/mail.svg" alt="Email">SaludTotal1@gmail.com</x-footer-icon>
                    <x-footer-icon icon="img/phone.svg" alt="Teléfono">+54 3777-569034</x-footer-icon>
                </div>
            </div>
        </footer>
        <!--/footer-->
        <a href="#top"
            id="btnScrollTop"
            style="display: none; position: fixed; bottom: 20px; right: 20px; background: #2563eb; color: white; padding: 10px; border-radius: 9999px;"
        >
            <i class="fa-solid fa-arrow-up"></i>
        </a>
    </div>

</div>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('js/form-consulta.js')}}"></script>
</body>
</html>
