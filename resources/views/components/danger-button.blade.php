<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 transition shadow-md active:scale-95 uppercase tracking-wide text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
