@props(['producto'])

<a href="{{ route('product.show', $producto->id_producto) }}"
   {{ $attributes->merge(['class' => 'group bg-white rounded-[2.5rem] p-6 shadow-md shadow-black/10 hover:shadow-lg hover:shadow-black/20 hover:-translate-y-2 transition-all duration-500 flex flex-col']) }}>
    
    <div class="h-56 w-full flex items-center justify-center mb-4 relative overflow-hidden">
        <img src="{{ $producto->imagen_principal }}" alt="{{ $producto->nombre }}"
             class="h-52 object-contain group-hover:scale-110 transition duration-500" />
    </div>
    
    <div class="mt-auto text-center">
        <h3 class="text-xl font-semibold text-gray-900 mb-1 group-hover:text-[#004689] transition tracking-tight">
            {{ $producto->nombre }}
        </h3>
        
        @if($producto->variantes->first()?->almacenamiento)
            <p class="text-sm text-gray-500 mb-2">{{ $producto->variantes->first()?->almacenamiento }}</p>
        @endif
        
        <div class="text-2xl font-bold text-[#004689] tracking-tight">
            {{ number_format($producto->precio, 2, ',', '.') }} €
        </div>
    </div>
</a>
