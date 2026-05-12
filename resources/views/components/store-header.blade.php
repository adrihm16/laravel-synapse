<div id="store-header-wrapper" class="relative">
    <!-- Header -->
    <header id="main-header" class="fixed top-0 left-0 w-full z-50 transition-transform duration-300 shadow-md font-sans">
    <div id="top-bar" class="fixed top-0 left-0 w-full z-50 bg-gradient-to-r from-[#004689] to-[#002F5C] text-white shadow-md h-[4.5rem]">
        <div class="flex items-center justify-between px-6 py-4 gap-4 h-full">
        <a href="{{ route('home') }}" class="font-extrabold text-2xl tracking-wide flex items-center gap-2">
            <span class="text-white">
            <svg class="w-10 h-10">
                <use xlink:href="{{ asset('assets/sprite.svg#icon-main') }}" />
            </svg>
            </span>
            SYNAPSE
        </a>

        <div class="hidden md:flex flex-1 max-w-2xl mx-auto px-6">
            <div class="w-full flex">
            <input type="text" placeholder="Buscar..." class="w-full py-2 px-4 rounded-l-full text-gray-900 bg-white focus:outline-none" />
            <button class="bg-white px-4 rounded-r-full hover:bg-gray-100 transition">
                <svg class="w-6 h-6">
                <use href="{{ asset('assets/sprite.svg#search-filled') }}"></use>
                </svg>
            </button>
            </div>
        </div>

        <div class="flex items-center gap-6 text-xl">
            <div class="relative">
            <button id="user-menu-btn" class="hover:text-gray-200 transition focus:outline-none flex items-center gap-2">
                <svg class="w-10 h-10">
                <use xlink:href="{{ asset('assets/sprite.svg#icon-user') }}" />
                </svg>
                @auth
                <span class="hidden md:inline text-sm font-medium">{{ auth()->user()->name }}</span>
                @endauth
            </button>

            @auth
            <!-- User Menu Card (Logged In) -->
            <div id="login-card" class="absolute top-14 -right-10 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-6 transform origin-top-right transition-all duration-300 scale-95 opacity-0 pointer-events-none invisible z-[100] text-slate-800 font-sans">
                <div class="absolute -top-2 right-12 w-4 h-4 bg-white/95 rotate-45 border-l border-t border-white/20"></div>

                <div class="text-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-[#004689] to-[#002F5C] rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>

                <div class="space-y-2 mb-4">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm font-medium text-gray-700">
                    <x-icon name="user" class="w-5 h-5 text-gray-400" />
                    Mi perfil
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm font-medium text-gray-700">
                    <x-icon name="clipboard" class="w-5 h-5 text-gray-400" />
                    Mis pedidos
                </a>
                @if(auth()->user()->rol === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm font-medium text-[#004689]">
                    <x-icon name="settings" class="w-5 h-5 text-[#004689]" />
                    Panel Admin
                </a>
                @endif
                </div>

                <div class="border-t border-gray-100 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-50 text-red-600 font-semibold text-sm hover:bg-red-100 transition">
                    <x-icon name="logout" class="w-5 h-5" />
                    Cerrar sesión
                    </button>
                </form>
                </div>
            </div>
            @else
            <!-- Login Card (Not Logged In) -->
            <div id="login-card" class="absolute top-14 -right-10 w-80 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-8 transform origin-top-right transition-all duration-300 scale-95 opacity-0 pointer-events-none invisible z-[100] text-slate-800 font-sans">
                <div class="absolute -top-2 right-12 w-4 h-4 bg-white/95 rotate-45 border-l border-t border-white/20"></div>

                <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-[#004689] mb-1">Bienvenido</h3>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Inicia sesión en tu cuenta</p>
                </div>

                <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-600 ml-1 uppercase tracking-wider">Email</label>
                    <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] outline-none transition-all text-sm font-medium" placeholder="nombre@email.com" required>
                    <x-icon name="mail" class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" />
                    </div>
                </div>

                <div class="space-y-1" x-data="{ passwordType: 'password' }">
                    <label class="block text-xs font-bold text-gray-600 ml-1 uppercase tracking-wider">Contraseña</label>
                    <div class="relative">
                    <input id="login-password" name="password" :type="passwordType" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] outline-none transition-all text-sm font-medium" placeholder="••••••••" required>
                    <x-icon name="lock" class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" />
                    <button type="button" @click="passwordType = (passwordType === 'password' ? 'text' : 'password')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                        <template x-if="passwordType === 'password'">
                            <x-icon name="eye" class="w-5 h-5" />
                        </template>
                        <template x-if="passwordType === 'text'">
                            <x-icon name="eye-slash" class="w-5 h-5" />
                        </template>
                    </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs my-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#004689] focus:ring-[#004689]">
                    <span class="text-gray-500 group-hover:text-[#004689] transition">Recuérdame</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[#004689] font-semibold hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-[#004689] to-[#002F5C] text-white py-3 rounded-xl font-bold text-sm tracking-wide hover:shadow-lg hover:shadow-[#004689]/30 hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                    INICIAR SESIÓN
                </button>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-4 text-center">
                <p class="text-xs text-gray-500 mb-3">¿No tienes una cuenta?</p>
                <a href="{{ route('register') }}" class="block w-full py-2.5 rounded-xl border-2 border-[#004689]/10 text-[#004689] font-bold text-sm hover:bg-[#004689]/5 hover:border-[#004689] transition-all duration-300 text-center">
                    Crear cuenta nueva
                </a>
                </div>
            </div>
            @endauth
            </div>
            <a href="{{ route('cart.index') }}" class="relative hover:text-gray-200 transition">
            <svg class="w-10 h-10">
                <use xlink:href="{{ asset('assets/sprite.svg#icon-cart') }}" />
            </svg>
            @auth
                @php $cartCount = auth()->user()->carritoItems()->sum('cantidad'); @endphp
                @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-xs font-bold px-2 rounded-full border-2 border-[#002F5C]">{{ $cartCount }}</span>
                @endif
            @endauth
            </a>
        </div>
        </div>
    </div>

    <!--Menu navbar-->
    <nav id="smart-nav" class="fixed top-[4.5rem] left-0 w-full z-40 bg-[#001a33] text-white py-3 px-6 border-t border-white/10 transition-transform duration-300 translate-y-0">
        <div id="menu-toggle" class="flex items-center justify-between">
        <div class="flex items-center gap-3 cursor-pointer">
            <x-icon name="menu" class="w-8 h-8 fill-current" />
        </div>

        <div class="hidden lg:flex items-center gap-8 font-bold text-sm tracking-wider uppercase">
            <a href="#" class="flex items-center gap-1">
            BLACK FRIDAY
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
            </a>
            <a href="#" class="flex items-center gap-1">
            OUTLET HOGAR
            <x-icon name="chevron-down" class="w-4 h-4" stroke-width="3" />
            </a>
            <a href="#"> BLOG </a>
        </div>
        </div>
    </nav>
    </header>

    <!--Menu lateral-->
    <div id="menu-overlay" class="fixed inset-0 hidden transition-opacity duration-300 opacity-0 z-50"></div>

    <div id="sidebar-menu" class="fixed top-[4.5rem] left-0 w-[17.5rem] h-[calc(100vh-4.5rem)] z-[70] bg-gradient-to-r from-[#004689] to-[#002F5C] bg-[length:100vw_100%] bg-left-top text-white transform -translate-x-full transition-transform duration-300 shadow-2xl overflow-y-auto">
    <div class="flex justify-end p-6">
        <button id="close-menu" class="text-white/80 hover:text-white transition">
        <x-icon name="x-mark" class="w-8 h-8" />
        </button>
    </div>
    <nav class="px-8 pb-10 space-y-6 text-lg font-medium">
        @auth
        <div class="flex items-center gap-3 pb-4 border-b border-white/20">
        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
            <span class="font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        </div>
        <div>
            <p class="font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-xs text-white/70">{{ auth()->user()->email }}</p>
        </div>
        </div>
        @endauth
        
        <a href="{{ route('home') }}" class="block hover:text-white transition">Inicio</a>
        <a href="{{ route('catalog.index', ['categories[]' => 1]) }}" class="block hover:text-white transition">Smartphones</a>
        <a href="{{ route('catalog.index', ['categories[]' => 2]) }}" class="block hover:text-white transition">Ordenadores</a>
        <a href="{{ route('catalog.index', ['categories[]' => 3]) }}" class="block hover:text-white transition">Tablets</a>
        
        <div class="border-t border-white/20 pt-4 mt-6">
        @auth
        <a href="{{ route('profile.edit') }}" class="block hover:text-white transition mb-6">Mi cuenta</a>
        <a href="{{ route('cart.index') }}" class="block hover:text-white transition mb-6">Carrito</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block text-red-300 hover:text-red-200 transition">Cerrar sesión</button>
        </form>
        @else
        <a href="{{ route('login') }}" class="block hover:text-white transition mb-6">Iniciar sesión</a>
        <a href="{{ route('register') }}" class="block hover:text-white transition">Crear cuenta</a>
        @endauth
        </div>
    </nav>
    </div>

    <!-- Espaciador real que ocupa lugar en el flujo -->
    <div class="h-[7.25rem]"></div>
</div>
