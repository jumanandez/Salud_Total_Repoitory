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
                    <i class="fas fa-bars pr-2 text-white text-3xl cursor-pointer" onclick="sidebarToggle()"></i>
                    <h1 class="text-white p-2">
                        <i class="fas fa-plus" style="color: #ec0909;"></i>
                        Salud Total
                    </h1>
                </div>
                <div class="p-1 flex flex-row items-center">
                    <a href="https://github.com/tailwindadmin/admin" class="text-white p-2 mr-2 no-underline hidden md:block lg:block">Github</a>


                    <img onclick="profileToggle()" class="inline-block h-8 w-8 rounded-full" src="https://avatars0.githubusercontent.com/u/4323180?s=460&v=4" alt="">
                    <a href="#" onclick="profileToggle()" class="text-white p-2 no-underline hidden md:block lg:block">Adam Wathan</a>
                    <div id="ProfileDropDown" class="rounded hidden shadow-md bg-white absolute pin-t mt-12 mr-1 pin-r">
                        <ul class="list-reset">
                          <li><a href="#" class="no-underline px-4 py-2 block text-black hover:bg-grey-light">My account</a></li>
                          <li><a href="#" class="no-underline px-4 py-2 block text-black hover:bg-grey-light">Notifications</a></li>
                          <li><hr class="border-t mx-2 border-grey-ligght"></li>
                          <li><a href="#" class="no-underline px-4 py-2 block text-black hover:bg-grey-light">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!--/Header-->

        <div class="flex flex-1">
            <!--Sidebar-->
            <aside id="sidebar" class="bg-side-nav  border-r border-side-nav">

                <ul class="list-reset flex flex-col">
                    <!-- Menu Item  -->
                    <a id="home" href="index.html"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                        <li class=" w-full h-full py-3 px-2 border-b border-light-border">
                            <div class="w-full">
                                <i class="fas fa-home float-top mx-2"></i>
                            </div>
                        </li>
                    </a>
                    <!-- End Menu Item  -->
                    <a href="index.html"
                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                        <li class=" w-full h-full py-3 px-2 border-b border-light-border">
                            <div class="w-full">
                                <i class="fas fa-calendar-alt float-top mx-2"></i>
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
