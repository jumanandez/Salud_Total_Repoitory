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
            <div class="flex justify-between">

                <div class="p-1 mx-3 inline-flex items-center">
                    <i class="fa-solid fa-bars pr-2 text-white text-3xl cursor-pointer" onclick="sidebarToggle()"></i>
                </div>

                <div class="p-2 flex flex-row items-center">
                    @guest
                        <a href="{{route('login')}}" class="text-white bg-green-dark hover:bg-green-800 p-2 rounded-full">
                            <span class="px-5">Pedir Turno</span>
                        </a>
                    @endguest
                    @auth
                        <a href="{{route('profesionales.index')}}" class="text-white bg-green-dark hover:bg-green-800 p-2 rounded-full">
                            <span class="px-5">Pedir Turno</span>
                        </a>
                    @endauth
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
                    <a href="index.html"
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
                            <a href="">
                                <span class="text-2xl">Consultas <i class="fa-solid fa-angle-down align-bottom"></i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            <a href="">
                                <span class="text-2xl">Contáctanos <i class="fa-solid fa-angle-down align-bottom"> </i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            <a href="{{route('profesionales.index')}}">
                                <span class="text-2xl">Médicos <i class="fa-solid fa-angle-down align-bottom"> </i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            <a href="">
                                <span class="text-2xl">Servicios Clínicos <i class="fa-solid fa-angle-down align-bottom"> </i></span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--/Shortcuts-->
                <!--Content-->
                 <!--img src="{asset('img/WelcomeFondo1.png')}}" alt="salud total logo" class="w-full h-1/2"-->
              <section class="relative z-0 bg-cover bg-center py-20" style="background-image: url('{{ asset('img/WelcomeFondo1.jpg') }}')" >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                  <h1 class="text-4xl font-bold text-black text-center mb-12 drop-shadow-lg">
                    Bienvenido a Salud Total
                  </h1>

                  <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                        <img
                  class="w-full h-48 object-cover object-center"
                  src="{{ asset('img/panel1.jpg') }}"
                  alt="Panel 1"
                />
                      <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Chequeos Médicos</h3>
                        <p class="text-gray-800">Realizarse un análisis de  sangre de forma  regular es  fundamental  para cuidar la salud. Este examen permite detectar de manera temprana posibles enfermedades, como la diabetes, el colesterol alto, problemas hepáticos, anemia o infecciones. Además, ayuda a monitorear el estado general del cuerpo y la efectividad de ciertos tratamientos si ya se está bajo cuidado médico.</p>
                      </div>
                    </div>

                    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                                <img
                  class="w-full h-48 object-cover object-center"
                  src="{{ asset('img/panel2.png') }}"
                  alt="Panel 1"
                />
                      <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">La importancia de tomarse la presion</h3>
                        <p class="text-gray-700">Tomarse la presión  arterial con un doctor permite evaluar el funcionamiento del corazón y el sistema circulatorio. Es un control simple, rápido y sin dolor, pero muy útil para detectar problemas como la hipertensión, una condición que muchas veces no presenta síntomas pero que puede causar serios daños si no se trata a tiempo.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <section class="bg-white py-10">
                <h1 class="text-2xl font-semibold text-center mb-8">Pedí tu turno</h1>

                <div class="flex justify-center items-center flex-wrap gap-x-12 gap-y-8">

                  <a href="/turnos/cardiologia" class="flex flex-col items-center hover:opacity-80 transition">
                    <img src="img/doctor.svg" alt="Cardiología" class="w-32 h-32 mb-2 rounded-xl p-3">
                    <span class="text-[#0C1C3C] font-semibold">Cardiología</span>
                  </a>

                  <a href="/turnos/ginecologia" class="flex flex-col items-center hover:opacity-80 transition">
                    <img src="img/doctor.svg" alt="Ginecología" class="w-32 h-32 mb-2 rounded-xl p-3">
                    <span class="text-[#0C1C3C] font-semibold">Ginecología</span>
                  </a>

                  <a href="/turnos/pediatria" class="flex flex-col items-center hover:opacity-80 transition">
                    <img src="img/doctor.svg" alt="Pediatría" class="w-32 h-32 mb-2 rounded-xl p-3">
                    <span class="text-[#0C1C3C] font-semibold">Pediatría</span>
                  </a>

                  <a href="/turnos/clinica-general" class="flex flex-col items-center hover:opacity-80 transition">
                    <img src="img/doctor.svg" alt="Clínica General" class="w-32 h-32 mb-2 rounded-xl p-3">
                    <span class="text-[#0C1C3C] font-semibold">Clínica General</span>
                  </a>

                </div>
              </section>
              <section class="relative z-0 bg-cover bg-center py-20" style="background-image: url('{{ asset('img/WelcomeFondo2.jpg') }}')" >
                  <h1 class="text-4xl font-bold text-black text-center mb-12 drop-shadow-lg">
                      NUESTROS SERVICIOS CLINICOS
                  </h1>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 px-8 max-w-6xl mx-auto">

                <div class="rounded-xl overflow-hidden shadow-md mb-6">
                <img src="img/Cardiologia.jpg" alt="Cardiologia" class="w-full h-48 object-cover" />
                <div class="p-4 bg-white">
                  <h3 class="text-lg font-bold">Cardiología</h3>
                    <p class="text-sm text-gray-700">
                      Ofrecemos un diagnóstico y tratamiento integral de las enfermedades cardiovasculares, asegurando una atención continua y de calidad.
                    </p>
                  </div>
                </div>
                <div class="rounded-xl overflow-hidden shadow-md mb-6">
                <img src="img/Pediatria.jpg" alt="Pediatria" class="w-full h-48 object-cover" />
                <div class="p-4 bg-white">
                  <h3 class="text-lg font-bold">Pediatria</h3>
                    <p class="text-sm text-gray-700">
                      Cuidamos el crecimiento y desarrollo saludable de los más pequeños, especializada desde la infancia hasta la adolescencia.
                    </p>
                  </div>
                </div>

                <div class="rounded-xl overflow-hidden shadow-md mb-6">
                <img src="img/Ginecologia.jpg" alt="Ginecologia" class="w-full h-48 object-cover" />
                <div class="p-4 bg-white">
                  <h3 class="text-lg font-bold">Ginecología</h3>
                    <p class="text-sm text-gray-700">
                      Brindamos servicios completos de salud femenina, desde chequeos rutinarios hasta tratamientos especializados.
                    </p>
                  </div>
                </div>

                <div class="rounded-xl overflow-hidden shadow-md mb-6">
                <img src="img/clinica-general.jpg" alt="Clinica General" class="w-full h-48 object-cover" />
                <div class="p-4 bg-white">
                  <h3 class="text-lg font-bold">Clinica General</h3>
                    <p class="text-sm text-gray-700">
                      Proporcionamos atención médica general con un enfoque en la prevención y la salud integral.
                    </p>
                  </div>
                </div>
              </section>
              <section class="relative z-0 bg-cover bg-center py-20" style="background-image: url('{{ asset('img/Consulta.jpg') }}')" >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                      <h1 class="text-4xl font-bold text-black text-center mb-12 drop-shadow-lg">
                    HACER UNA CONSULTA ESPECIAL
                  </h1>
                      <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Nombre</h3>
                        <input type="text" class="border border-gray-300 rounded-lg p-2 w-full mb-4" placeholder="Ingrese su nombre">
                        
                        <h3 class="text-xl font-semibold mb-2">Email</h3>
                        <input type="email" class="border border-gray-300 rounded-lg p-2 w-full mb-4" placeholder="Ingrese su email">
                        
                        <h3 class="text-xl font-semibold mb-2">Teléfono</h3>
                        <input type="text" class="border border-gray-300 rounded-lg p-2 w-full mb-4" placeholder="Ingrese su teléfono">
                        
                        <h3 class="text-xl font-semibold mb-2">Mensaje</h3>
                        <textarea class="border border-gray-300 rounded-lg p-2 w-full mb-4" rows="4" placeholder="Ingrese su mensaje"></textarea>
                        
                        <button class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600">
                          Enviar
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
                <!--/Content-->
              </section>
            </main>

        </div>
        <footer class="bg-grey-darkest text-white p-2">
            <div class="flex flex-1 mx-auto">&copy; My Design</div>
            <div class="flex flex-1 mx-auto">Distributed by:  <a href="https://themewagon.com/" target=" _blank">Themewagon</a></div>
        </footer>
        <!--/footer-->

    </div>

</div>
<script src="{{asset('js/main.js')}}"></script>
</body>
</html>
