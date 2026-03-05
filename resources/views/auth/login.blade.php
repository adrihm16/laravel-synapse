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
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
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
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required
                        class="appearance-none relative block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] focus:bg-white transition-all text-sm pr-12 pl-10"
                        placeholder="••••••••">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" data-target="password">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="eye" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path class="eye" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path class="eye-off hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
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
