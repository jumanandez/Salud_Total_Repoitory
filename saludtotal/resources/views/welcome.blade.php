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
                        <a href="{{route('turnos.create')}}" class="text-white bg-green-dark hover:bg-green-800 p-2 rounded-full">
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
            <main class="bg-white-300 flex-1 p-3 overflow-hidden">
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
                 <img src="{{asset('img/WelcomeFondo1.png')}}" alt="salud total logo" class="w-full h-1/2">
                <!--/Content-->

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
