@props(['title', 'value', 'description' => null, 'icon', 'alert' => false])

<div class="rounded-3xl shadow-lg p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300 {{ $alert ? 'bg-red-600 text-white' : 'bg-[#004689] text-white' }}">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 {{ $alert ? 'bg-white/20' : 'bg-white/10' }} rounded-2xl flex items-center justify-center text-white">
            <x-icon :name="$icon" class="w-6 h-6" />
        </div>
        <span class="text-xs font-semibold {{ $alert ? 'text-white/80' : 'text-white/70' }} uppercase tracking-wider">{{ $title }}</span>
    </div>
    <div>
        <p class="text-3xl font-bold">{{ $value }}</p>
        @if($description)
            <p class="text-sm {{ $alert ? 'text-white/80' : 'text-white/70' }} mt-1">{{ $description }}</p>
        @endif
    </div>
</div>
