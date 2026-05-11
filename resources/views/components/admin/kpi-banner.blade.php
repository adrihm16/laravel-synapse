@props(['stats'])

<div class="bg-gradient-to-r from-[#004689] to-[#002F5C] rounded-[2.5rem] p-8 lg:p-10 shadow-lg text-white flex flex-col lg:flex-row items-center justify-around gap-8 border border-white/5">
    @foreach($stats as $index => $stat)
        <div class="flex items-center gap-6 flex-1 justify-center lg:justify-start">
            <div>
                <p class="text-sm font-bold text-white/50 uppercase tracking-[0.15em] mb-1">{{ $stat['title'] }}</p>
                <p @class([
                    'text-3xl font-extrabold tracking-tight leading-none',
                    'text-red-400' => $stat['alert'] ?? false,
                    'text-white' => !($stat['alert'] ?? false)
                ])>{{ $stat['value'] }}</p>
                @if(isset($stat['description']))
                    <p class="text-sm text-white/30 mt-2 font-medium">{{ $stat['description'] }}</p>
                @endif
            </div>
        </div>

        @if(!$loop->last)
            <!-- Thin divider with top/bottom margin -->
            <div class="hidden lg:block h-26 w-px bg-white/10 my-auto mx-4"></div>
            <div class="lg:hidden w-3/4 h-px bg-white/10 mx-auto"></div>
        @endif
    @endforeach
</div>
