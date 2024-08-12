<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido a ElectroSearch</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="relative min-h-screen flex flex-col items-center justify-center">
       
    

        <main class="relative z-10 flex flex-col items-center justify-center text-center p-6 bg-white rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold mb-6">Bienvenido a ElectroSearch</h1>
            <p class="text-lg mb-6">Explora, encuentra y gestiona tus necesidades de manera más eficiente.</p>
            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">Comenzar</a>
        </main>

        <footer class="relative z-10 mt-6 text-gray-600">
            <p>&copy; {{ date('Y') }} ElectroSearch. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
