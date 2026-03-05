@extends('layouts.store')
@section('title', 'Catálogo - Synapse')

@section('styles')
    /* Custom scrollbar for filters */
    .filters-scroll::-webkit-scrollbar { width: 4px; }
    .filters-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .filters-scroll::-webkit-scrollbar-thumb { background: #004689; border-radius: 10px; }

    /* Price range slider styling */
    input[type="range"] {
      -webkit-appearance: none; appearance: none;
      width: 100%; height: 6px; border-radius: 5px;
      background: #e5e7eb; outline: none;
    }
    input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none; width: 18px; height: 18px;
      border-radius: 50%; background: #004689; cursor: pointer;
      box-shadow: 0 2px 6px rgba(0, 70, 137, 0.3); transition: all 0.2s;
    }
    input[type="range"]::-webkit-slider-thumb:hover { transform: scale(1.1); background: #002F5C; }
    .custom-checkbox { accent-color: #004689; }
@endsection

@section('content')
  <!-- Main Content -->
  <div class="flex-grow py-8 px-4 md:px-6 lg:px-8 font-sans">
    <div class="max-w-[95%] mx-auto">

      <!-- Breadcrumb & Title -->
      <div class="mb-8">
        <nav class="text-sm text-gray-500 mb-4">
          <a href="{{ route('home') }}" class="hover:text-[#004689] transition">Inicio</a>
          <span class="mx-2">/</span>
          <span class="text-gray-800 font-medium">Catálogo</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <h1 class="text-3xl md:text-4xl font-semibold text-gray-900">Catálogo de Productos</h1>
          <p class="text-gray-500">Mostrando <span class="font-semibold text-gray-700">{{ $productos->count() }}</span> productos</p>
        </div>
      </div>

      <!-- Mobile Filter Toggle -->
      <button id="mobile-filter-toggle"
        class="lg:hidden w-full mb-6 flex items-center justify-center gap-3 bg-white rounded-2xl py-4 px-6 shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100">
        <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4..."></path>
        </svg>
        <span class="font-semibold text-gray-700">Filtrar y Ordenar</span>
      </button>

      <div class="flex flex-col lg:flex-row gap-8">

        <!-- Filters Sidebar -->
        <aside id="filters-sidebar" class="hidden lg:block w-full lg:w-72 xl:w-80 flex-shrink-0">
          <div class="bg-white rounded-3xl shadow-md p-6 sticky top-[150px]">
             <!-- (Filters kept simple for migration mockup) -->
             <div class="pb-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Categorías</h3>
                <div class="space-y-3">
                  <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" checked class="custom-checkbox w-5 h-5 rounded border-gray-300">
                    <span class="text-sm text-gray-700 group-hover:text-[#004689] transition">Smartphones</span>
                  </label>
                  <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" class="custom-checkbox w-5 h-5 rounded border-gray-300">
                    <span class="text-sm text-gray-700 group-hover:text-[#004689] transition">Ordenadores</span>
                  </label>
                </div>
              </div>
          </div>
        </aside>

        <!-- Products Grid -->
        <section class="flex-grow">
          <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach($productos as $producto)
            @php
                $variante = $producto->variantes->first();
                $precio = $variante ? $variante->precio : 0;
                $imagen = $variante && $variante->imagen ? asset($variante->imagen) : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
            @endphp
            <a href="{{ route('product.show', $producto->id_producto) }}"
              class="group bg-white rounded-[2rem] p-6 shadow-md shadow-black/10 hover:shadow-xl hover:shadow-black/20 hover:-translate-y-2 transition-all duration-500 flex flex-col">
              <div class="h-48 w-full flex items-center justify-center mb-4 relative overflow-hidden">
                <img src="{{ $imagen }}" alt="{{ $producto->nombre }}"
                  class="h-44 object-contain group-hover:scale-110 transition duration-500" />
              </div>
              <div class="mt-auto text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-[#004689] transition tracking-tight">{{ $producto->nombre }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ $variante ? $variante->almacenamiento : '' }}</p>
                <div class="text-2xl font-bold text-[#004689] tracking-tight">{{ number_format($precio, 2, ',', '.') }} €</div>
              </div>
            </a>
            @endforeach

          </div>
        </section>
      </div>
    </div>
  </div>
@endsection
