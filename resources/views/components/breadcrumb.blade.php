@props(['items' => []])

<nav {{ $attributes->merge(['class' => 'flex mb-6 text-sm']) }} aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-500 hover:text-[#004689] transition-colors duration-200">
                <x-icon name="home" class="w-4 h-4 mr-2" />
                Inicio
            </a>
        </li>
        @foreach($items as $item)
            <li>
                <div class="flex items-center">
                    <x-icon name="chevron-right" class="w-4 h-4 text-gray-400" />
                    @if(isset($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="ml-1 text-gray-500 hover:text-[#004689] md:ml-2 font-medium transition-colors duration-200">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="ml-1 text-gray-900 md:ml-2 font-semibold truncate max-w-[150px] md:max-w-none" aria-current="page">
                            {{ $item['label'] }}
                        </span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
