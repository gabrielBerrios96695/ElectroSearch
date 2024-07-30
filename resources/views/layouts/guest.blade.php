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

    <!-- Custom Styles -->
    <style>
        .bg-gradient {
            background: linear-gradient(45deg, 
                #4facfe, #00f2fe, #6a11cb, #2575fc,
                #ff5f6d, #ffc371, #ff7e5f, #feb47b,
                #12c2e9, #c471ed, #f64f59, #667eea);
            background-size: 1600% 1600%;
            animation: gradientAnimation 30s ease infinite;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 100%; }
            25% { background-position: 50% 50%; }
            50% { background-position: 100% 0%; }
            75% { background-position: 50% 50%; }
            100% { background-position: 0% 100%; }
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient">
        <div>
            <a href="/" wire:navigate>
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
