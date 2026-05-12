@extends('layouts.admin')

@section('title', 'Nueva Categoría - Synapse Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.categories.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <div>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Nueva Categoría</h1>
            <p class="text-gray-500 mt-1">Crea una nueva categoría de productos.</p>
        </div>
    </div>

    <!-- Form Card -->
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-lg p-8 space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                placeholder="Ej: Smartphones, Tablets, Accesorios..." />
            @error('nombre')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div x-data="{ preview: null }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Imagen</label>
            <div class="flex items-start gap-6">
                <!-- Preview -->
                <div class="w-28 h-28 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
                    <template x-if="preview">
                        <img :src="preview" class="w-full h-full object-cover" />
                    </template>
                    <template x-if="!preview">
                        <x-icon name="image" class="w-10 h-10 text-gray-300" />
                    </template>
                </div>

                <!-- File input -->
                <div class="flex-1 space-y-2">
                    <input type="file" name="imagen" id="imagen" accept="image/*"
                        @change="if ($event.target.files[0]) { preview = URL.createObjectURL($event.target.files[0]) }"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#004689]/10 file:text-[#004689] hover:file:bg-[#004689]/20 file:transition file:cursor-pointer" />
                    <p class="text-xs text-gray-400">JPG, PNG o WebP. Máx. 2 MB.</p>
                    @error('imagen')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.categories.index') }}" class="rounded-full border-2 border-gray-200 text-gray-700 font-semibold py-3 px-8 transition hover:border-gray-400 active:scale-95">
                Cancelar
            </a>
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-8 transition shadow-md active:scale-95">
                Crear Categoría
            </button>
        </div>
    </form>
</div>
@endsection
