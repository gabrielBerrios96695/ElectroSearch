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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient">
        <div>
            <a href="/" wire:navigate>
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-lg mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">
            <!-- Botones para Login y Registro -->
            <a href="{{ route('login') }}" class="inline-block w-full sm:w-auto mb-4">
                <x-secondary-button class="w-full">
                    {{ __('Formulario de Inicio') }}
                </x-secondary-button>
            </a>
            <a href="{{ route('register') }}" class="inline-block w-full sm:w-auto">
                <x-secondary-button class="w-full">
                    {{ __('Registrarse') }}
                </x-secondary-button>
            </a>
            <div class="w-full sm:max-w-lg mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
