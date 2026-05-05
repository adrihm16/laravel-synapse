@props(['currentStep' => 1])

@php
    $steps = [
        1 => 'Envío',
        2 => 'Resumen',
        3 => 'Confirmación',
    ];
@endphp

<div class="flex items-center justify-center mb-10">
    @foreach($steps as $number => $label)
        <div class="flex items-center">
            {{-- Step circle --}}
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm transition-all duration-300
                    {{ $number < $currentStep
                        ? 'bg-[#004689] text-white'
                        : ($number === $currentStep
                            ? 'bg-[#004689] text-white ring-4 ring-[#004689]/20'
                            : 'bg-gray-200 text-gray-500') }}">
                    @if($number < $currentStep)
                        {{-- Checkmark for completed steps --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        {{ $number }}
                    @endif
                </div>
                <span class="mt-2 text-xs font-medium tracking-wide
                    {{ $number <= $currentStep ? 'text-[#004689]' : 'text-gray-400' }}">
                    {{ $label }}
                </span>
            </div>

            {{-- Connecting line --}}
            @if($number < count($steps))
                <div class="w-16 sm:w-24 h-0.5 mx-2 mt-[-1.25rem] transition-all duration-300
                    {{ $number < $currentStep ? 'bg-[#004689]' : 'bg-gray-200' }}">
                </div>
            @endif
        </div>
    @endforeach
</div>
