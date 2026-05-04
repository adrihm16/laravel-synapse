@extends('layouts.store')
@section('title', $producto->nombre . ' - Synapse')

@section('content')
  <!-- Main Content -->
  <main class="min-h-screen pt-10 pb-10 px-4 font-sans">
      <div class="max-w-[95%] mx-auto mb-6">
          <x-breadcrumb :items="[
              ['label' => 'Catálogo', 'url' => route('catalog.index')],
              ['label' => $producto->nombre]
          ]" />
      </div>
      <div class="max-w-[95%] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12 items-stretch">
          
          <!-- Image Carousel Column -->
          <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-md flex items-center justify-between relative h-full min-h-[31.25rem]">
              <button id="prev-btn" class="text-gray-300 hover:text-gray-800 text-4xl transition duration-200 px-2 z-10">
                  &#10094;
              </button>
              <div class="w-full flex justify-center absolute inset-0 items-center pointer-events-none">
                  <img id="product-img" src="{{ $producto->imagen_principal }}" alt="{{ $producto->nombre }}"
                      class="max-h-[25rem] object-contain pointer-events-auto transition-opacity duration-300">
              </div>
              <button id="next-btn" class="text-gray-300 hover:text-gray-800 text-4xl transition duration-200 px-2 z-10">
                  &#10095;
              </button>
          </div>

          <!-- Product Details Column -->
          <div class="bg-white rounded-3xl p-8 shadow-md flex flex-col h-full justify-between">
              <div class="flex flex-col gap-6">
                  <h1 class="text-4xl font-semibold text-gray-900">{{ $producto->nombre }}</h1>

                  <!-- Colors -->
                  <div>
                      <h3 class="text-gray-500 font-medium mb-3">Color</h3>
                          @php $firstColor = true; @endphp
                          @foreach($producto->colores_unicos as $var)
                              <div class="color-option {{ $firstColor ? 'border-black ring-1 ring-black' : 'border-gray-200 hover:border-gray-400' }} rounded-lg px-4 py-3 flex items-center gap-2 cursor-pointer transition"
                                  data-color="{{ $var->color }}">
                                  <span class="w-4 h-4 rounded-full {{ $var->color_class }}"></span>
                                  <span class="text-sm font-medium">{{ $var->color }}</span>
                              </div>
                              @php $firstColor = false; @endphp
                          @endforeach
                      </div>
                  </div>

                  <!-- Storage -->
                  <div>
                      <h3 class="text-gray-500 font-medium mb-3">Almacenamiento</h3>
                      <div id="storage-options" class="flex flex-col gap-3">
                          @php $firstStorage = true; @endphp
                          @foreach($producto->variantes as $var)
                              <div class="storage-option border {{ $var->stock > 0 && $firstStorage ? 'border-black ring-1 ring-black' : 'border-gray-200' }} {{ $var->stock <= 0 ? 'bg-gray-50 cursor-not-allowed text-gray-400' : 'cursor-pointer hover:border-gray-400 transition-all duration-200' }} rounded-xl p-4 flex justify-between items-center text-sm font-medium"
                                  data-storage="{{ $var->almacenamiento }}" data-price="{{ number_format($var->precio, 2, ',', '.') }}" data-id="{{ $var->id_variante }}">
                                  
                                  @if($var->stock <= 0)
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $var->almacenamiento }}</span>
                                        <span class="text-xs mt-1">Agotado</span>
                                    </div>
                                    <span class="px-3 py-1 bg-gray-200 rounded-full text-[0.625rem] font-bold uppercase text-gray-500">No disponible</span>
                                  @else
                                    <span>{{ $var->almacenamiento }}</span>
                                    <span>{{ number_format($var->precio, 2, ',', '.') }} €</span>
                                    @php $firstStorage = false; @endphp
                                  @endif
                              </div>
                          @endforeach
                      </div>
                  </div>

                  <!-- Gift box -->
                  <div>
                      <p class="text-gray-700 font-medium mb-2">Llévatelo de regalo</p>
                      <div class="border border-gray-200 rounded-xl p-4 flex gap-4 items-center">
                          <div class="w-16 h-16 flex-shrink-0 bg-gray-50 rounded-md flex items-center justify-center">
                              <span class="text-2xl">🎁</span>
                          </div>
                          <div class="text-xs">
                              <p class="text-gray-600 mb-1">DJI Osmo Mobile 7 Gimbal</p>
                              <div class="flex items-center gap-2">
                                  <span class="line-through text-gray-400">99,00 €</span>
                                  <span class="text-red-500 font-bold">Ahorra 99,00 €</span>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Total & Add to Cart Container -->
                  <div class="mt-6 pt-6 border-t border-gray-100 text-center space-y-4">
                      <form action="{{ route('cart.add') }}" method="POST">
                          @csrf
                          <input type="hidden" name="id_variante" id="id_variante_input" value="{{ $producto->variantes->first()?->id_variante }}">
                          
                          <div class="text-gray-600 text-lg mb-4">
                              Total:
                              <div class="text-4xl font-bold text-gray-900 mt-1" id="total-price">{{ number_format($producto->precio, 2, ',', '.') }}€</div>
                          </div>
                          <button type="submit"
                              class="w-full bg-[#004689] hover:bg-[#002F5C] text-white font-semibold text-lg py-4 rounded-full transition shadow-lg transform active:scale-95">
                              Añadir al carrito
                          </button>

                      </form>
                  </div>
              </div>
          </div>
      </div>

      <section class="max-w-[95%] mx-auto w-full mt-12">
              <div class="mb-12 text-center">
                  <h2 class="text-3xl md:text-4xl font-extralight color-[#000000] mt-3 tracking-tight">
                      Otros Productos
                  </h2>
              </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
          @foreach($relacionados as $rel)
            <x-product-card :producto="$rel" />
          @endforeach
      </div>
      <div class="flex justify-center mt-8">
          <a href="{{ route('catalog.index') }}"
              class="group inline-flex items-center gap-2 bg-[#004689] text-white font-medium px-10 py-3 rounded-full hover:opacity-90 transition text-lg">
              Ver más
          </a>
      </div>
  </section>
  </main>
@endsection

@push('scripts')
<script>
    // Carrusel de imágenes de producto
    const productImg = document.getElementById('product-img');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');

    if (productImg && prevBtn && nextBtn) {
        const images = [
            @if($producto->imagenes->count() > 0)
                @foreach($producto->imagenes as $img)
                    "{{ asset($img->ruta) }}",
                @endforeach
            @else
                productImg.src, // Fallback if no gallery images exist
            @endif
        ];
        let currentIndex = 0;

        function updateImage(index) {
            productImg.style.opacity = '0';
            setTimeout(() => {
                productImg.src = images[index];
                productImg.style.opacity = '1';
            }, 300);
        }

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            updateImage(currentIndex);
        });

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateImage(currentIndex);
        });
    }

    // Selección de opciones de producto
    document.addEventListener('DOMContentLoaded', () => {
        // Colores
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', () => {
                colorOptions.forEach(opt => {
                    opt.classList.remove('border-black', 'ring-1', 'ring-black');
                    opt.classList.add('border-gray-200');
                });
                option.classList.remove('border-gray-200');
                option.classList.add('border-black', 'ring-1', 'ring-black');
            });
        });

        // Almacenamiento
        const storageOptions = document.querySelectorAll('.storage-option');
        const priceDisplay = document.getElementById('total-price');
        const variantInput = document.getElementById('id_variante_input');

        storageOptions.forEach(option => {
            if(!option.classList.contains('cursor-not-allowed')) {
                option.addEventListener('click', () => {
                    storageOptions.forEach(opt => {
                        if(!opt.classList.contains('cursor-not-allowed')) {
                            opt.classList.remove('border-black', 'ring-1', 'ring-black');
                            opt.classList.add('border-gray-200');
                        }
                    });
                    option.classList.remove('border-gray-200');
                    option.classList.add('border-black', 'ring-1', 'ring-black');

                    // Update price and hidden input
                    const newPrice = option.getAttribute('data-price');
                    const newId = option.getAttribute('data-id');
                    if(newPrice) priceDisplay.textContent = newPrice + '€';
                    if(newId) variantInput.value = newId;
                });
            }
        });
    });
</script>
@endpush
