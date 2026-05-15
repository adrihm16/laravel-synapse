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
  <div class="flex-grow py-8 px-4 md:px-6 lg:px-8 font-sans" x-data="{ openFilters: false }">
    <div class="max-w-[95%] mx-auto">

      <!-- Breadcrumb & Title -->
      <div class="mb-8">
        <x-breadcrumb :items="[['label' => 'Catálogo']]" />
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <h1 class="text-3xl md:text-4xl font-semibold text-gray-900">Catálogo de Productos</h1>
          <p class="text-gray-500">Mostrando <span class="font-semibold text-gray-700">{{ $productos->firstItem() }}</span> - <span class="font-semibold text-gray-700">{{ $productos->lastItem() }}</span> de <span class="font-semibold text-gray-700">{{ $productos->total() }}</span> productos</p>
        </div>
      </div>

      <!-- Mobile Filter Toggle -->
      <button id="mobile-filter-toggle"
        type="button"
        @click="openFilters = !openFilters"
        class="lg:hidden w-full mb-6 flex items-center justify-center gap-3 bg-white rounded-2xl py-4 px-6 shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100">
        <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>
        <span class="font-semibold text-gray-700">Filtrar y Ordenar</span>
      </button>

      <div class="flex flex-col lg:flex-row gap-8">

        <!-- Filters Sidebar -->
        <aside id="filters-sidebar" 
               style="display: none;"
               x-show="openFilters"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 -translate-y-4"
               x-transition:enter-end="opacity-100 translate-y-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="opacity-100 translate-y-0"
               x-transition:leave-end="opacity-0 -translate-y-4"
               class="lg:!block w-full lg:w-72 xl:w-80 flex-shrink-0">
          <form method="GET" action="{{ route('catalog.index') }}" class="bg-white rounded-3xl shadow-md p-6 sticky top-[9.375rem]">
             
             <!-- Sorting -->
             <div class="pb-6 border-b border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Ordenar por</h3>
                <select name="sort" class="w-full rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] outline-none py-2 px-3 text-sm text-gray-700 transition">
                    <option value="">Relevancia (Por defecto)</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Novedades</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                </select>
             </div>

             <!-- Categories -->
             <div class="pb-6 border-b border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Categorías</h3>
                <div class="space-y-3 max-h-48 overflow-y-auto filters-scroll pr-2">
                  <label class="flex items-center gap-3 cursor-pointer group pb-3 border-b border-gray-100">
                    <input type="checkbox" name="featured" value="1"
                           {{ request('featured') ? 'checked' : '' }}
                           class="custom-checkbox w-5 h-5 rounded border-gray-300">
                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#004689] transition">Solo destacados</span>
                  </label>
                  @foreach($categorias as $categoria)
                  <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="categories[]" value="{{ $categoria->id_categoria }}"
                           {{ in_array($categoria->id_categoria, request('categories', [])) ? 'checked' : '' }}
                           class="custom-checkbox w-5 h-5 rounded border-gray-300">
                    <span class="text-sm text-gray-700 group-hover:text-[#004689] transition">{{ $categoria->nombre }}</span>
                  </label>
                  @endforeach
                </div>
              </div>

              <!-- Brands -->
              @if($brands->isNotEmpty())
              <div class="pb-6 border-b border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Marcas</h3>
                <div class="space-y-3 max-h-48 overflow-y-auto filters-scroll pr-2">
                  @foreach($brands as $brand)
                  <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="brands[]" value="{{ $brand }}"
                           {{ in_array($brand, request('brands', [])) ? 'checked' : '' }} 
                           class="custom-checkbox w-5 h-5 rounded border-gray-300">
                    <span class="text-sm text-gray-700 group-hover:text-[#004689] transition">{{ $brand }}</span>
                  </label>
                  @endforeach
                </div>
              </div>
              @endif

              <!-- Price -->
              <div class="pb-6 mb-2">
                <h3 class="font-semibold text-gray-800 mb-4">Precio</h3>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" 
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] outline-none py-2 px-3 text-sm text-gray-700 text-center transition">
                    <span class="text-gray-400">-</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" 
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#004689]/20 focus:border-[#004689] outline-none py-2 px-3 text-sm text-gray-700 text-center transition">
                </div>
              </div>

              <button type="submit" class="w-full bg-[#004689] hover:bg-[#002F5C] text-white font-semibold py-3 rounded-full transition shadow-md active:scale-95 text-sm uppercase tracking-wide">
                  Aplicar Filtros
              </button>
              
              @if(request()->hasAny(['categories', 'brands', 'min_price', 'max_price', 'sort', 'featured']))
              <a href="{{ route('catalog.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-red-500 transition">
                  Limpiar Filtros
              </a>
              @endif

          </form>
        </aside>

        <!-- Products Grid -->
        <section class="flex-grow">
          <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach($productos as $producto)
            <x-product-card :producto="$producto" />
            @endforeach

          </div>

          <!-- Pagination -->
          <div class="mt-12">
              {{ $productos->withQueryString()->links('vendor.pagination.synapse') }}
          </div>
        </section>
      </div>
    </div>
  </div>
@endsection
