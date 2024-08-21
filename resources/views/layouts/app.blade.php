<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="{{ asset('leaflet/leaflet.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden'" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="sidebar fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-800 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <span class="text-white text-2xl mx-2 font-semibold">ElectroSearch</span>
                </div>
            </div>
            <nav class="mt-10">
                @if(Auth::check())
                    @if(Auth::user()->isOrganizador() && Auth::user()->passwordUpdate == true)
                        <a class="nav-link flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700" href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i>
                            <span class="mx-3">Dashboard</span>
                        </a>
                    @else
                        @if(Auth::user()->isAdmin())
                            <a class="nav-link flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>
                                <span class="mx-3">Dashboard</span>
                            </a>
                            <div x-data="{ open: false }" class="relative">
                                <!-- Botón para abrir/cerrar el menú -->
                                <a @click="open = !open" class="nav-link flex items-center mt-4 py-2 px-6 cursor-pointer text-white hover:bg-gray-700">
                                    <i class="fas fa-cogs"></i>
                                    <span class="mx-3">Administración</span>
                                    <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-auto"></i>
                                </a>
                                <!-- Menú desplegable -->
                                <div x-show="open" class="pl-6">
                                    <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-gray-600 text-white" href="{{ route('users.index') }}">
                                        <i class="fas fa-users"></i>
                                        <span class="mx-3">Usuarios</span>
                                    </a>
                                
                                    <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-gray-600 text-white" href="{{ route('type_points.index') }}">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="mx-3">Tipos de Puntos</span>
                                    </a>
                                    <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-gray-600 text-white" href="{{ route('collection_points.index') }}">
                                        <i class="fas fa-trash"></i>
                                        <span class="mx-3">Puntos de Recolección</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->isOrganizador())
                            <div x-data="{ open: false }" class="relative">
                                <!-- Botón para abrir/cerrar el menú -->
                                <a @click="open = !open" class="nav-link flex items-center mt-4 py-2 px-6 cursor-pointer text-white hover:bg-gray-700">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span class="mx-3">Panel de Control</span>
                                    <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-auto"></i>
                                </a>
                                <!-- Menú desplegable -->
                                <div x-show="open" class="pl-6">
                                    
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="header flex justify-between items-center py-4 px-6 bg-white shadow-md">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="breadcrumbs">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Menu</a>@yield('breadcrumbs')
                    </div>
                </div>

                <div x-data="{ open: false }" @click.away="open = false" class="relative user-menu">
                    
                    <button class="p-2 bg-green-500 text-white hover:bg-green-600 focus:outline-none rounded-md">
                        <i class="fas fa-envelope"></i>
                    </button>

                    
                    <button @click="open = !open" class="p-2 bg-green-500 text-white hover:bg-green-600 focus:outline-none rounded-md">
                        <a class="text-white hover:text-gray-700">{{ Auth::user()->name }}</a>
                            
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                        <a class="block px-4 py-2 text-sm hover:bg-gray-200 text-black">Perfil</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="block px-4 py-2 text-sm hover:bg-gray-200 w-full text-left text-black">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                    
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content flex-1 overflow-x-hidden overflow-y-auto">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
