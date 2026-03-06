@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none border-gray-200 bg-gray-50 focus:bg-white transition-all shadow-sm w-full']) }}>
