<div>
    @if($carrito->isEmpty())
      <div class="bg-white rounded-[2.5rem] p-12 shadow-md border border-gray-100 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="text-2xl font-medium text-gray-900 mb-2">Tu carrito está vacío</h3>
        <p class="text-gray-500 mb-8">Parece que aún no has añadido ningún producto.</p>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-[#004689] text-white font-medium px-8 py-3 rounded-full hover:opacity-90 transition">
            Volver a la tienda
        </a>
      </div>
    @else
    <!--Carrito-->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
      <div class="lg:col-span-9 bg-white rounded-[2.5rem] p-6 md:p-10 shadow-md border border-gray-100 min-h-[31.25rem]">
        <div id="cart-items-container" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

          @php $subtotal = 0; @endphp
          @foreach($carrito as $item)
          @php
            $producto = $item->variante->producto;
            $precio = $item->variante->precio;
            $subtotal += $precio * $item->cantidad;
            $imagen = $item->variante->imagen ? asset($item->variante->imagen) : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
          @endphp
          <div x-data="{ removing: false }" 
               x-show="!removing" 
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="opacity-100 transform scale-100"
               x-transition:leave-end="opacity-0 transform scale-90"
               class="product-item flex flex-col items-center relative" 
               wire:key="{{ $item->id_carrito }}">
            
            <!-- Quantity Controls -->
            <div class="absolute top-2 right-14 z-10 flex items-center gap-1 bg-white rounded-full p-1 shadow-sm border border-gray-100">
                <button @click="if({{ $item->cantidad }} <= 1) { removing = true; setTimeout(() => $wire.decrement({{ $item->id_carrito }}), 250); } else { $wire.decrement({{ $item->id_carrito }}); }" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition font-medium focus:outline-none focus:bg-gray-200">
                    &minus;
                </button>
                
                <span class="text-sm font-semibold text-gray-800 w-4 text-center select-none">{{ $item->cantidad }}</span>
                
                <button wire:click="increment({{ $item->id_carrito }})" class="w-7 h-7 flex items-center justify-center rounded-full text-[#004689] hover:bg-[#004689] hover:text-white transition font-medium focus:outline-none focus:ring-2 focus:ring-[#002F5C]">
                    &#43;
                </button>
            </div>

            <!-- Remove Button -->
            <button @click="removing = true; setTimeout(() => $wire.remove({{ $item->id_carrito }}), 250)" class="absolute top-2 right-2 z-10 text-red-400 hover:text-red-600 p-2 bg-white rounded-full shadow-sm hover:shadow-md transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>

            <a href="{{ route('product.show', $producto->id_producto) }}" class="bg-white border border-gray-200 rounded-[1.5rem] p-4 shadow-sm w-full aspect-square flex flex-col items-center justify-center relative group hover:shadow-md transition-shadow">
              <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="h-32 object-contain mb-3" />
              <h3 class="text-lg font-medium text-slate-900 text-center leading-tight group-hover:text-[#004689] transition-colors">{{ $producto->nombre }}</h3>
              <p class="text-sm text-gray-500">{{ $item->variante->almacenamiento }}</p>
              <p class="text-base font-bold text-gray-500 mt-1">{{ number_format($precio, 2, ',', '.') }}€</p>
            </a>

            <div class="flex items-center gap-3 mt-4">
              <p class="text-gray-500 text-sm">Cantidad: <span class="font-bold text-gray-800">{{ $item->cantidad }}</span></p>
            </div>
          </div>
          @endforeach

        </div>
      </div>

      <!--Resumen-->
      <div class="lg:col-span-3 bg-white rounded-[2.5rem] p-6 shadow-md border border-gray-100 min-h-[31.25rem] flex flex-col sticky top-[9.375rem]">
        <h2 class="text-2xl font-light text-center mb-8 text-slate-900">Resumen</h2>

        <div class="space-y-4 mb-8 flex-grow">
          <div class="flex justify-between text-sm font-medium text-slate-700">
            <span>Subtotal</span>
            <span>{{ number_format($subtotal, 2, ',', '.') }}€</span>
          </div>
          <div class="flex justify-between text-sm font-medium text-slate-700">
            <span>Envío</span>
            <span>0,00€</span>
          </div>
          <div class="border-t border-gray-100 my-4"></div>
        </div>

        <div class="mb-8">
          <div class="flex justify-between items-center">
            <span class="text-lg font-bold text-slate-900">Total</span>
            <span class="text-xl font-bold text-slate-900">{{ number_format($subtotal, 2, ',', '.') }}€</span>
          </div>
        </div>

        <a href="{{ route('checkout.index') }}" class="w-full bg-[#004689] text-white py-3.5 rounded-full font-medium text-base hover:bg-[#002F5C] transition shadow-lg hover:shadow-xl hover:-translate-y-1 transform duration-300 block text-center">
          Ir a pagar
        </a>
      </div>
    </div>
    @endif
</div>
