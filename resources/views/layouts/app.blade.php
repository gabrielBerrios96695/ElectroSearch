<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="{{ asset('leaflet/leaflet.css') }}" rel="stylesheet">
    <style>
        /* Elimina el estilo del mapa aquí para que no se muestre globalmente */
    </style>
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden'" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-800 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <span class="text-white text-2xl mx-2 font-semibold">Laravel</span>
                </div>
            </div>
            <nav class="mt-10">
                @if(Auth::check())
                     @if(Auth::user()->isAdmin())
                        <a class="flex items-center mt-4 py-2 px-6 text-gray-500 hover:bg-gray-700 hover:text-white" href="{{ route('dashboard') }}">
                            <span class="mx-3">Dashboard</span>
                        </a>
                        <a class="flex items-center mt-4 py-2 px-6 text-gray-500 hover:bg-gray-700 hover:text-white" href="{{ route('users.index') }}">
                            <span class="mx-3">Usuarios</span>
                        </a>
                        <a class="flex items-center mt-4 py-2 px-6 text-gray-500 hover:bg-gray-700 hover:text-white" href="{{ route('store.index') }}">
                            <span class="mx-3">Tiendas</span>
                        </a>
                    @endif

                    @if(Auth::user()->isCliente())
                        <a class="flex items-center mt-4 py-2 px-6 text-gray-500 hover:bg-gray-700 hover:text-white" href="{{ route('users.index') }}">
                            <span class="mx-3">Usuarios</span>
                        </a>
                    @endif
                @endif
                <!-- Add more links as needed -->
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-indigo-600">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
                        {{ Auth::user()->name }}
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Perfil</a>
                        <div class="relative">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 w-full text-left">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Breadcrumbs -->
            <div class="container mx-auto px-6 py-4">
                @yield('breadcrumb')
            </div>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts') <!-- Esto permite agregar scripts adicionales desde las vistas -->
</body>
</html>
