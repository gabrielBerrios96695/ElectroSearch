<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="{{ asset('leaflet/leaflet.css') }}" rel="stylesheet">
    <!-- En la sección <head> -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-light font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden'" class="fixed inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>
        <aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="sidebar fixed z-30 inset-y-0 left-0 w-64 transition duration-300 bg-gradient-to-r from-blue-800 to-blue-600 text-white overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center mt-8">
                <span class="text-2xl font-semibold">ElectroSearch</span>
            </div>
            <nav class="mt-10">
                @if(Auth::check())
                    <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="mx-3">Dashboard</span>
                    </a>
                    @if(Auth::user()->isAdmin())
                        <div x-data="{ open: false }" class="relative">
                            <a @click="open = !open" class="nav-link flex items-center mt-4 py-2 px-6 cursor-pointer hover:bg-blue-500">
                                <i class="fas fa-cogs"></i>
                                <span class="mx-3">Administración</span>
                                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-auto"></i>
                            </a>
                            <div x-show="open" class="pl-6">
                                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-400" href="{{ route('users.index') }}">
                                    <i class="fas fa-users"></i>
                                    <span class="mx-3">Usuarios</span>
                                </a>
                                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-400" href="{{ route('store.index') }}">
                                    <i class="fas fa-store"></i>
                                    <span class="mx-3">Tiendas</span>
                                </a>
                                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-400" href="{{ route('store.show') }}">
                                    <i class="fas fa-store"></i>
                                    <span class="mx-3">Buscar en tiendas</span>
                                </a>
                                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-400" href="{{ route('products.index') }}">
                                    <i class="fas fa-tags"></i>
                                    <span class="mx-3">Productos</span>
                                </a>
                                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-400" href="{{ route('categories.index') }}">
                                    <i class="fas fa-tags"></i>
                                    <span class="mx-3">Categorias</span>
                                </a>
                                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-400" href="{{ route('sales.index') }}">
                                    <i class="fas fa-tags"></i>
                                    <span class="mx-3">Venta</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="header flex justify-between items-center p-4 bg-blue-800 text-white shadow">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-200 lg:hidden">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="breadcrumbs">
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-gray-100">Menu</a>@yield('breadcrumbs')
                </div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-4 py-2 text-sm hover:bg-blue-700">
                        {{ Auth::user()->name }}
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                        <a class="block px-4 py-2 text-sm text-black hover:bg-gray-200">Perfil</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="block px-4 py-2 text-sm text-black hover:bg-gray-200 w-full text-left">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto p-6 bg-gray-100">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>
</html>
