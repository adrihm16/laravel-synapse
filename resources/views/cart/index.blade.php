@extends('layouts.store')
@section('title', 'Carrito - Synapse')

@section('content')
  <!--Main-->
  <main class="flex-grow max-w-[95%] mx-auto px-4 md:px-8 py-10 w-full font-sans">
    <div class="mb-12 text-center">
      <h2 class="text-3xl md:text-4xl font-extralight color-[#000000] mt-3 tracking-tight">
        Tu Carrito
      </h2>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

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
      <div class="lg:col-span-9 bg-white rounded-[2.5rem] p-6 md:p-10 shadow-md border border-gray-100 min-h-[500px]">
        <div id="cart-items-container" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

          @php $subtotal = 0; @endphp
          @foreach($carrito as $item)
          @php
            $producto = $item->variante->producto;
            $precio = $item->variante->precio;
            $subtotal += $precio * $item->cantidad;
            $imagen = $item->variante->imagen ? asset($item->variante->imagen) : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
          @endphp
          <div class="product-item flex flex-col items-center relative">
            
            <form action="{{ route('cart.remove') }}" method="POST" class="absolute top-2 right-2 z-10">
                @csrf
                <input type="hidden" name="id_carrito" value="{{ $item->id_carrito }}">
                <button type="submit" class="text-red-400 hover:text-red-600 p-2 bg-white rounded-full shadow-sm hover:shadow-md transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>

            <div class="bg-white border border-gray-200 rounded-[1.5rem] p-4 shadow-sm w-full aspect-square flex flex-col items-center justify-center relative group hover:shadow-md transition-shadow">
              <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="h-32 object-contain mb-3" />
              <h3 class="text-lg font-medium text-slate-900 text-center leading-tight">{{ $producto->nombre }}</h3>
              <p class="text-sm text-gray-500">{{ $item->variante->almacenamiento }}</p>
              <p class="text-base font-bold text-gray-500 mt-1">{{ number_format($precio, 2, ',', '.') }}€</p>
            </div>

            <div class="flex items-center gap-3 mt-4">
              <p class="text-gray-500 text-sm">Cantidad: <span class="font-bold text-gray-800">{{ $item->cantidad }}</span></p>
            </div>
          </div>
          @endforeach

        </div>
      </div>

      <!--Resumen-->
      <div class="lg:col-span-3 bg-white rounded-[2.5rem] p-6 shadow-md border border-gray-100 min-h-[500px] flex flex-col sticky top-[150px]">
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

        <button class="w-full bg-[#004689] text-white py-3.5 rounded-full font-medium text-base hover:bg-[#002F5C] transition shadow-lg hover:shadow-xl hover:-translate-y-1 transform duration-300">
          Ir a pagar
        </button>
      </div>
    </div>
    @endif
  </main>
@endsection
