@extends('layouts.store')
@section('title', 'Synapse - Inicio')

@section('content')
  <!-- Hero Section -->
  <section class="w-full bg-slate-900">
    <a href="{{ route('catalog.index') }}" class="block group relative overflow-hidden">
      <img src="{{ asset('assets/HeroOnePlus15Mobile.png') }}" alt="OnePlus 15"
        class="w-full h-auto object-cover md:hidden transition-all duration-700 group-hover:scale-105 group-hover:brightness-110" />
      <img src="{{ asset('assets/HeroOnePlus15.png') }}" alt="OnePlus 15"
        class="hidden w-full h-auto object-cover md:block transition-all duration-700 group-hover:scale-[1.01] group-hover:brightness-110" />
    </a>
  </section>

  <!-- Productos Destacados -->
  <section class="max-w-[95%] mx-auto px-6 py-10">
    <div class="mb-12 text-center">
      <h2 class="text-3xl md:text-4xl font-extralight color-[#000000] mt-3 tracking-tight">
        Productos Destacados
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
      @foreach($destacados as $producto)
        @php
            $variante = $producto->variantes->first();
            $precio = $variante ? $variante->precio : 0;
            $imagen = $variante && $variante->imagen ? asset($variante->imagen) : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
        @endphp
      <a href="{{ route('product.show', $producto->id_producto) }}"
        class="group bg-white rounded-[2.5rem] p-8 shadow-md shadow-black/20 hover:shadow-lg hover:shadow-black/40 hover:-translate-y-2 transition-all duration-500 flex flex-col">
        <div class="h-64 w-full flex items-center justify-center mb-4 relative">
          <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="h-60 object-contain group-hover:scale-110 transition duration-500" />
        </div>
        <div class="mt-auto text-center">
          <h3 class="text-2xl font-semibold text-[#000000] mb-2 group-hover:text-[#000000] transition tracking-tight">
            {{ $producto->nombre }}
          </h3>
          <div class="text-3xl font-normal text-[#000000] tracking-tight">
            {{ number_format($precio, 2, ',', '.') }} €
          </div>
        </div>
      </a>
      @endforeach
    </div>
    
    <div class="flex justify-center mt-8">
      <a href="{{ route('catalog.index') }}"
        class="group inline-flex items-center gap-2 bg-[#004689] text-white font-medium px-10 py-3 rounded-full hover:opacity-90 transition text-lg">
        Ver más
      </a>
    </div>
  </section>

  <!--Categorías de productos-->
  <section class="max-w-[95%] mx-auto px-6 py-10">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 justify-items-center">
      @php
        $catImages = [
            'Smartphones' => 'nothingPhone1.png',
            'Ordenadores' => 'macbookAir.png',
            'Tablets' => 'ipadPro.png',
            'Accesorios' => 'pixelWatch4.png',
            'Hogar' => 'CafeteraXiaomi.png'
        ];
      @endphp
      
      @foreach($categorias as $cat)
        @php
            $catImg = isset($catImages[$cat->nombre]) ? $catImages[$cat->nombre] : 'iPadPro.png';
        @endphp
      <a href="{{ route('catalog.index', ['categoria' => $cat->id_categoria]) }}" class="group flex flex-col items-center w-full max-w-[260px]">
        <div
          class="bg-white rounded-[2.5rem] p-8 shadow-md shadow-black/20 hover:shadow-lg hover:shadow-black/30 hover:-translate-y-1 transition-all duration-500 w-full aspect-square flex items-center justify-center">
          <img src="{{ asset('assets/' . $catImg) }}" alt="{{ $cat->nombre }}"
            class="h-4/5 w-4/5 object-contain group-hover:scale-110 transition duration-500" />
        </div>
        <span class="mt-5 text-lg md:text-2xl font-medium text-[#000000] text-center tracking-tight">
          {{ $cat->nombre }}
        </span>
      </a>
      @endforeach
    </div>
  </section>
@endsection
