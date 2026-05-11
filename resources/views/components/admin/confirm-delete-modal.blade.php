@props(['id', 'title', 'action', 'buttonText' => 'Eliminar'])

<div x-data="{ confirmDelete: false }" class="inline-block">
    <button @click="confirmDelete = true" {{ $attributes->merge(['class' => 'p-2 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition']) }}>
        {{ $trigger ?? '' }}
        @if(!isset($trigger))
            <x-icon name="trash" class="w-5 h-5" />
        @endif
    </button>

    <div x-show="confirmDelete" x-transition x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="confirmDelete = false">
        <div class="fixed inset-0 bg-black/50" @click="confirmDelete = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full z-10">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-icon name="exclamation-triangle" class="w-8 h-8 text-red-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $title }}</h3>
                <div class="text-gray-500 mb-6">
                    {{ $slot }}
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="confirmDelete = false" class="flex-1 rounded-full border-2 border-gray-200 text-gray-700 font-semibold py-3 px-4 transition hover:border-gray-400 active:scale-95">
                    Cancelar
                </button>
                <form method="POST" action="{{ $action }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 transition shadow-md active:scale-95">
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
