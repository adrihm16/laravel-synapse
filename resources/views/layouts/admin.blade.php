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

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Top Bar -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-sm shadow-md h-[72px] flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-[#004689] focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
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
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
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

    <div class="flex pt-[72px] min-h-screen">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:sticky top-[calc(72px+1rem)] lg:translate-x-0 z-40 w-64 bg-white rounded-3xl shadow-xl m-4 flex flex-col h-[calc(100vh-72px-2rem)] transition-transform duration-300 ease-in-out">
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="font-medium">Productos</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span class="font-medium">Categorías</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-[#004689] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-[#004689]' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Usuarios</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="font-medium">Volver a la tienda</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8 w-full max-w-[100vw] lg:max-w-[calc(100vw-18rem)] overflow-x-hidden">
            @yield('content')
        </main>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>
    </div>
    
    @stack('scripts')
</body>
</html>
