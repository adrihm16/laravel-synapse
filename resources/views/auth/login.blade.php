<x-guest-layout>
    <div class="text-center mb-6">
        <h3 class="mt-2 text-3xl font-bold text-gray-900 mb-1">Bienvenido</h3>
        <p class="mt-2 text-sm text-gray-600">¿No tienes cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-[#004689] hover:text-[#002F5C]">Crea una cuenta aquí</a>
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

    <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <div class="relative">
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus
                        value="{{ old('email') }}"
                        class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm pl-10"
                        placeholder="ejemplo@correo.com">
                        <x-icon name="mail" class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" />
                </div>
            </div>

            <div x-data="{ passwordType: 'password' }">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <div class="relative">
                    <input id="password" name="password" :type="passwordType" required
                        class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm pr-12 pl-10"
                        placeholder="••••••••">
                    <x-icon name="lock" class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" />
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

        <div class="flex items-center justify-between text-sm my-4">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#004689] focus:ring-[#004689] mt-0.5">
                <span class="text-gray-500 group-hover:text-[#004689] transition">Recuérdame</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-[#004689] font-medium hover:underline">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit"
            class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-[#004689] to-[#002F5C] hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#004689] transition-all shadow-lg shadow-[#004689]/30 hover:shadow-xl hover:shadow-[#004689]/40 mt-6 md:mt-8">
            INICIAR SESIÓN
        </button>
    </form>
</x-guest-layout>
