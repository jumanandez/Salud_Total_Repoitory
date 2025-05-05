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
        <header class="bg-nav">
            <div class="flex justify-between">

                <div class="p-1 mx-3 inline-flex items-center">
                    <i class="fa-solid fa-bars pr-2 text-white text-3xl cursor-pointer" onclick="sidebarToggle()"></i>
                </div>

                <div class="p-1 inline-flex items-center">
                    <a href="#">
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
                    <a id="home" href="{{route('welcome')}}"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline border-b border-light-border">
                        <li class="w-full h-full py-3 px-2 ">
                            <div class="w-full justify-center">
                                <i class="fas fa-home float-top mx-2 text-xl"></i>
                            </div>
                        </li>
                    </a>
                    <!-- End Menu Item  -->
                    <a href="index.html"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline border-b border-light-border">
                        <li class="w-full h-full py-3 px-2 ">
                            <div class="w-full justify-center">
                                <i class="fas fa-calendar-alt float-top mx-2 text-2xl"></i>
                            </div>
                        </li>
                    </a>

                </ul>
            </aside>
            <!--/Sidebar-->
            <!--Main-->
            <main class="bg-white-300 flex-1 p-3 overflow-hidden">
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
</body>
</html>
