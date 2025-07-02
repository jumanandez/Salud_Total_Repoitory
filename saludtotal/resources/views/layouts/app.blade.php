<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Css -->
    <link rel="stylesheet" href={{asset("css/styles.css")}}>
    <link rel="stylesheet" href={{asset("css/all.css")}}>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @viteReactRefresh
    <title>{{$title ?? "Salud Total" }}</title>
</head>

<body>
<!--Container -->
<div class="mx-auto bg-grey-400">
    <!--Screen-->
    <div class="min-h-screen flex flex-col">
        <!--Header Section Starts Here-->
        <header class="bg-nav">
            <div class="flex justify-between">
                <div class="p-1 mx-3 inline-flex items-center">
                    <i class="fa-solid fa-bars pr-2 text-white text-3xl cursor-pointer" onclick="sidebarToggle()"></i>
                </div>
                <div class="p-1 inline-flex items-center">
                    <a href="{{ route('welcome') }}">
                        <img src="{{asset('img/salud_total.svg')}}" alt="salud total logo" class="w-32 h-10">
                    </a>
                </div>
            </div>
        </header>
        <!--/Header-->

        <div class="flex flex-1">
            <!--Sidebar-->
            <aside id="sidebar" class="bg-side-nav  border-r border-side-nav">
                <ul class="list-reset flex flex-col">
                    <!-- Menu Item  -->
                    <a title="Inicio" id="home" href="{{route('welcome')}}"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline border-b border-light-border">
                        <li class="w-full h-full py-3 px-2 ">
                            <div class="w-full justify-center">
                                <i class="fas fa-home float-top mx-2 text-xl"></i>
                            </div>
                        </li>
                    </a>
                    <!-- End Menu Item  -->
                    <a href="http://saludtotal.test/turnos/mis-turnos"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline border-b border-light-border">
                        <li class="w-full h-full py-3 px-2 ">
                            <div class="w-full justify-center">
                                <i class="fas fa-calendar-alt float-top mx-2 text-2xl"></i>
                            </div>
                        </li>
                    </a>
                    @auth
                    <form action="{{ route('logout') }}" method='POST'>
                        @csrf
                        <button title="Cerrar sesión" class="text-white font-semibold p-2 no-underline hidden md:block lg:block  border-b border-light-border "
                        type='submit' :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                            <i class="fa-solid fa-right-from-bracket float-top mx-2 text-2xl" style="transform: scaleX(-1);"></i>
                        </button>
                    </form>
                @endauth
                </ul>
            </aside>
            <!--/Sidebar-->
            <!--Main-->
            <main class="bg-white-300 flex-1 px-32 py-6 overflow-hidden">
                {{$slot}}
            </main>

        </div>
        <!--Footer-->
        <footer class="bg-grey-darkest text-white p-2">
            <div class="flex flex-1 mx-auto">&copy; My Design</div>
            <div class="flex flex-1 mx-auto">Distributed by:  <a href="https://themewagon.com/" target=" _blank">Themewagon</a></div>
        </footer>
        <!--/footer-->

    </div>

</div>
<script src="{{asset('js/main.js')}}"></script>
@yield('scripts')
</body>
</html>
