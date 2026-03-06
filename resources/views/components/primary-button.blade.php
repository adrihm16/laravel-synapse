<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-[#004689] hover:bg-[#002F5C] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95 uppercase tracking-wide text-sm focus:outline-none focus:ring-2 focus:ring-[#004689] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
