@extends('layouts.admin')

@section('title', 'Crear Usuario - Synapse Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Nuevo Usuario</h1>
            <p class="text-gray-500 mt-1">Crea una nueva cuenta de usuario en la plataforma.</p>
        </div>
    </div>

    <!-- Form Card -->
    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-3xl shadow-lg p-8 space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                placeholder="Nombre completo" />
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                placeholder="usuario@email.com" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role -->
        <div>
            <label for="rol" class="block text-sm font-semibold text-gray-700 mb-2">Rol</label>
            <select name="rol" id="rol"
                class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                <option value="cliente" {{ old('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('rol')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
            <input type="password" name="password" id="password"
                class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                placeholder="Mínimo 8 caracteres" />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                placeholder="Repite la contraseña" />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" class="rounded-full border-2 border-gray-200 text-gray-700 font-semibold py-3 px-8 transition hover:border-gray-400 active:scale-95">
                Cancelar
            </a>
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-8 transition shadow-md active:scale-95">
                Crear Usuario
            </button>
        </div>
    </form>
</div>
@endsection
