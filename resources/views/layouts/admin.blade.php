<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin - Synapse')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/favicon.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Top Bar -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-sm shadow-md h-[72px] flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-[#004689] focus:outline-none">
                <x-icon name="menu" class="w-6 h-6" />
            </button>
            
            <a href="{{ route('admin.dashboard') }}" class="font-extrabold text-2xl tracking-wide flex items-center gap-2 text-[#004689]">
                <svg class="w-8 h-8">
                    <use xlink:href="{{ asset('assets/sprite.svg#icon-main') }}" />
                </svg>
                <span>SYNAPSE <span class="font-light text-gray-400 text-lg">| Admin</span></span>
            </a>
        </div>

        <div class="flex items-center gap-4 relative" x-data="{ userMenuOpen: false }">
            <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center gap-2 focus:outline-none">
                <div class="w-10 h-10 bg-gradient-to-r from-[#004689] to-[#002F5C] rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="hidden md:block font-medium text-gray-700">{{ auth()->user()->name }}</span>
                <x-icon name="chevron-down" class="w-4 h-4 text-gray-500" />
            </button>

            <!-- User Dropdown -->
            <div x-show="userMenuOpen" x-transition x-cloak class="absolute right-0 top-12 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                <div class="px-4 py-2 border-b border-gray-50">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mi Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:sticky top-[calc(72px+1rem)] lg:translate-x-0 z-40 w-64 bg-white rounded-3xl shadow-xl m-4 flex flex-col h-[calc(100vh-72px-2rem)] transition-transform duration-300 ease-in-out">
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <x-icon name="dashboard" class="w-5 h-5" />
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <x-icon name="box" class="w-5 h-5" />
                    <span class="font-medium">Productos</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <x-icon name="tag" class="w-5 h-5" />
                    <span class="font-medium">Categorías</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <x-icon name="users" class="w-5 h-5" />
                    <span class="font-medium">Usuarios</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.orders.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" />
                    </svg>
                    <span class="font-medium">Pedidos</span>
                </a>

                <a href="{{ route('admin.stats.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.stats.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    <span class="font-medium">Estadísticas</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">
                    <x-icon name="chevron-left" class="w-5 h-5" />
                    <span class="font-medium">Volver a la tienda</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8 w-full max-w-[100vw] lg:max-w-[calc(100vw-18rem)] overflow-x-hidden pt-20 lg:pt-24">
            @yield('content')
        </main>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>
    </div>
    
    @stack('scripts')
</body>
</html>
