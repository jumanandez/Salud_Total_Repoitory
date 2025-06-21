<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href={{asset("css/styles.css")}}>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!--Header Section Starts Here-->
        <header class="bg-nav">
            <div class="flex justify-end">
                <div class="p-1 inline-flex items-center">
                    <a href="{{ route('welcome') }}">
                        <img src="{{asset('img/salud_total.svg')}}" alt="salud total logo" class="w-32 h-10">
                    </a>
                </div>
            </div>
        </header>
        <!--/Header-->
        <div class="min-h-screen flex flex-col sm:justify-content-center items-center pt-6 sm:pt-0 bg-gray-100" style="background-image: url('{{ asset('img/login-background.jpg') }}'); background-size: cover; background-position: center;">
            {{ $slot }}
        </div>
    </body>
</html>
