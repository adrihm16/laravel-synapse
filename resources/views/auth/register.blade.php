<x-guest-layout>
    <div class="text-center">
        <h2 class="mt-2 text-3xl font-bold text-gray-900">
            Crear cuenta
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-[#004689] hover:text-[#002F5C]">Inicia sesión</a>
        </p>
    </div>

    @if ($errors->any())
    <div class="mt-8 p-4 bg-red-50 border border-red-200 rounded-xl">
        <ul class="text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <li class="flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-4 h-4 flex-shrink-0" />
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                <input id="name" name="name" type="text" required
                    value="{{ old('name') }}"
                    class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm"
                    placeholder="Tu nombre">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    value="{{ old('email') }}"
                    class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm"
                    placeholder="ejemplo@correo.com">
            </div>

            <div x-data="{ passwordType: 'password' }">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <div class="relative">
                    <input id="password" name="password" :type="passwordType" required
                        class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm pr-12"
                        placeholder="Mínimo 8 caracteres">
                    <button type="button" @click="passwordType = (passwordType === 'password' ? 'text' : 'password')" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <template x-if="passwordType === 'password'">
                            <x-icon name="eye" class="w-5 h-5" />
                        </template>
                        <template x-if="passwordType === 'text'">
                            <x-icon name="eye-slash" class="w-5 h-5" />
                        </template>
                    </button>
                </div>
            </div>

            <div x-data="{ passwordType: 'password' }">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" :type="passwordType" required
                        class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm pr-12"
                        placeholder="Repite tu contraseña">
                    <button type="button" @click="passwordType = (passwordType === 'password' ? 'text' : 'password')" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <template x-if="passwordType === 'password'">
                            <x-icon name="eye" class="w-5 h-5" />
                        </template>
                        <template x-if="passwordType === 'text'">
                            <x-icon name="eye-slash" class="w-5 h-5" />
                        </template>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit"
            class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-[#004689] to-[#002F5C] hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#004689] transition-all shadow-lg shadow-[#004689]/30 hover:shadow-xl hover:shadow-[#004689]/40 mt-6 md:mt-8">
            Crear cuenta
        </button>
    </form>

    <div class="relative mt-8">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">O regístrate con</span>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <button type="button"
            class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
            <x-icon name="google" class="w-5 h-5" />
            Google
        </button>
        <button type="button"
            class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
            <x-icon name="apple" class="w-5 h-5" />
            Apple
        </button>
    </div>
</x-guest-layout>
