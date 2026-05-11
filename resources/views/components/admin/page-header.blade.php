@props(['title', 'description' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">{{ $title }}</h1>
        @if($description)
            <p class="text-gray-500 mt-1">{{ $description }}</p>
        @endif
    </div>
    @if($slot->isNotEmpty())
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
