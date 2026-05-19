@extends('layouts.store')
@section('title', $producto->nombre . ' - Synapse')

@section('content')
@php
    $variantsData = $producto->variantes->map(function($v) {
        return [
            'id' => $v->id_variante,
            'precio' => number_format($v->precio, 2, ',', '.'),
            'stock' => $v->stock,
            'valores' => $v->valores->pluck('id_valor')->toArray()
        ];
    })->values()->toJson();

    // To set initial selection, pick the first variant that has stock, or just the first one.
    $firstVariant = $producto->variantes->where('stock', '>', 0)->first() ?? $producto->variantes->first();
    
    $initialSelections = [];
    if ($firstVariant) {
        foreach ($firstVariant->valores as $valor) {
            $initialSelections[$valor->id_grupo] = $valor->id_valor;
        }
    }
    $initialSelectionsJson = json_encode($initialSelections);

    // Map id_valor → imagen_url for color values that have a single thumbnail image
    $colorThumbnails = [];
    foreach ($producto->gruposOpciones as $grupo) {
        if ($grupo->tipo === 'color') {
            foreach ($grupo->valores as $valor) {
                if ($valor->imagen) {
                    $colorThumbnails[$valor->id_valor] = $valor->imagen_url;
                }
            }
        }
    }
@endphp

  <!-- Main Content -->
  <main class="min-h-screen pt-10 pb-10 px-4 font-sans" x-data="productSelector">
      <div class="max-w-[95%] mx-auto mb-6">
          <x-breadcrumb :items="[
              ['label' => 'Catálogo', 'url' => route('catalog.index')],
              ['label' => $producto->nombre]
          ]" />
      </div>
      <div class="max-w-[95%] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12 items-stretch">
          
          <!-- Image Carousel Column -->
          <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-md flex items-center justify-between h-full min-h-[31.25rem] overflow-hidden gap-2">
              <button @click="prevImage()" x-show="images.length > 1" class="flex-shrink-0 text-gray-300 hover:text-gray-800 text-4xl transition duration-200 px-2 z-10">
                  &#10094;
              </button>
              <div class="flex-1 overflow-hidden" style="height: 26rem;">
                  <img :src="mainImage" alt="{{ $producto->nombre }}"
                      class="w-full h-full object-contain pointer-events-auto transition-opacity duration-300"
                      :class="{ 'opacity-0': isTransitioning, 'opacity-100': !isTransitioning }">
              </div>
              <button @click="nextImage()" x-show="images.length > 1" class="flex-shrink-0 text-gray-300 hover:text-gray-800 text-4xl transition duration-200 px-2 z-10">
                  &#10095;
              </button>
          </div>

          <!-- Product Details Column -->
          <div class="bg-white rounded-3xl p-8 shadow-md flex flex-col h-full justify-between">
              <div class="flex flex-col gap-6">
                  <h1 class="text-4xl font-semibold text-gray-900">{{ $producto->nombre }}</h1>

                  <!-- Dynamic Option Groups -->
                  @foreach($producto->gruposOpciones as $grupo)
                  <div>
                      <h3 class="text-gray-500 font-medium mb-3">{{ $grupo->nombre }}</h3>
                      @if($grupo->tipo === 'color')
                          <div class="flex flex-wrap gap-3">
                              @foreach($grupo->valores as $valor)
                                  <div @click="selectOption({{ $grupo->id_grupo }}, {{ $valor->id_valor }}, '{{ $valor->imagen_url ?? '' }}')"
                                      :class="selections[{{ $grupo->id_grupo }}] === {{ $valor->id_valor }} ? 'border-black ring-1 ring-black' : 'border-gray-200 hover:border-gray-400'"
                                      class="rounded-lg px-4 py-3 flex items-center gap-2 cursor-pointer transition border bg-white">
                                      @if($valor->hex_code)
                                          <span class="w-4 h-4 rounded-full border border-gray-200" style="background-color: {{ $valor->hex_code }}"></span>
                                      @endif
                                      <span class="text-sm font-medium">{{ $valor->nombre }}</span>
                                  </div>
                              @endforeach
                          </div>
                      @else
                          <div class="flex flex-col gap-3">
                              @foreach($grupo->valores as $valor)
                                  <div @click="selectOption({{ $grupo->id_grupo }}, {{ $valor->id_valor }}, '')"
                                      :class="selections[{{ $grupo->id_grupo }}] === {{ $valor->id_valor }} ? 'border-black ring-1 ring-black' : 'border-gray-200 hover:border-gray-400'"
                                      class="rounded-xl p-4 flex justify-between items-center text-sm font-medium cursor-pointer transition-all duration-200 border bg-white">
                                      <span>{{ $valor->nombre }}</span>
                                      @if($valor->precio_extra > 0)
                                        <span class="text-gray-400">+{{ number_format($valor->precio_extra, 2, ',', '.') }}€</span>
                                      @endif
                                  </div>
                              @endforeach
                          </div>
                      @endif
                  </div>
                  @endforeach

                  <!-- Total & Add to Cart Container -->
                  <div class="mt-6 pt-6 border-t border-gray-100 text-center space-y-4">
                      <form action="{{ route('cart.add') }}" method="POST">
                          @csrf
                          <input type="hidden" name="id_variante" :value="currentVariant ? currentVariant.id : ''">
                          
                          <div class="text-gray-600 text-lg mb-4">
                              Total:
                              <div class="text-4xl font-bold text-gray-900 mt-1" x-text="currentVariant ? currentVariant.precio + '€' : '---'"></div>
                          </div>

                          <div x-show="!currentVariant" class="text-red-500 mb-4 text-sm font-medium" style="display: none;">Combinación no disponible</div>
                          <div x-show="currentVariant && currentVariant.stock <= 0" class="text-red-500 mb-4 text-sm font-medium" style="display: none;">Agotado temporalmente</div>

                          <button type="submit"
                              :disabled="!currentVariant || currentVariant.stock <= 0"
                              :class="!currentVariant || currentVariant.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#002F5C] shadow-lg transform active:scale-95'"
                              class="w-full bg-[#004689] text-white font-semibold text-lg py-4 rounded-full transition">
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
document.addEventListener('alpine:init', () => {
    Alpine.data('productSelector', () => ({
        variants: {!! $variantsData !!},
        selections: {!! $initialSelectionsJson !!},
        currentVariant: null,
        isTransitioning: false,
        currentImageIndex: 0,

        // Global gallery (no color selected or color has no dedicated gallery)
        globalImages: [
            @php $galleryImages = $producto->imagenes->count() > 0 ? $producto->imagenes : $producto->todasImagenes @endphp
            @if($galleryImages->count() > 0)
                @foreach($galleryImages as $img)
                    "{{ $img->url }}",
                @endforeach
            @else
                '{{ $producto->imagen_principal }}'
            @endif
        ],

        // Per-color gallery map: { id_valor: [url, ...] }
        colorGalleries: @json($colorGalleries),

        // Per-color single thumbnail fallback: { id_valor: url }
        colorThumbnails: @json($colorThumbnails),

        images: [],
        mainImage: '',

        init() {
            this.images = [...this.globalImages];
            this.mainImage = this.images[0] ?? '{{ $producto->imagen_principal }}';
            this.updateVariant();
        },

        selectOption(groupId, valueId, imageUrl) {
            this.selections[groupId] = valueId;
            this._applyColorGallery(imageUrl);
            this.updateVariant();
        },

        _applyColorGallery(clickedImageUrl = null) {
            // 1. If any selected color has a dedicated multi-image gallery, use it
            for (const valueId of Object.values(this.selections)) {
                const gallery = this.colorGalleries[valueId];
                if (gallery && gallery.length > 0) {
                    this._swapGallery(gallery);
                    return;
                }
            }

            // 2. Restore global images
            this._swapGallery(this.globalImages);

            // 3. If the clicked color has a thumbnail, navigate to it inside the carousel
            if (clickedImageUrl) {
                const idx = this.images.indexOf(clickedImageUrl);
                if (idx !== -1) {
                    this.currentImageIndex = idx;
                    this.mainImage = this.images[idx];
                } else {
                    this.mainImage = clickedImageUrl;
                }
            }
        },

        _swapGallery(newImages) {
            if (JSON.stringify(this.images) === JSON.stringify(newImages)) return;
            this.isTransitioning = true;
            setTimeout(() => {
                this.images = [...newImages];
                this.currentImageIndex = 0;
                this.mainImage = this.images[0] ?? '{{ $producto->imagen_principal }}';
                this.isTransitioning = false;
            }, 150);
        },

        updateVariant() {
            const selectedValueIds = Object.values(this.selections).map(Number);
            this.currentVariant = this.variants.find(v =>
                selectedValueIds.every(id => v.valores.includes(id)) && v.valores.length === selectedValueIds.length
            );
        },

        nextImage() {
            if (this.images.length <= 1) return;
            this.isTransitioning = true;
            setTimeout(() => {
                this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
                this.mainImage = this.images[this.currentImageIndex];
                this.isTransitioning = false;
            }, 150);
        },

        prevImage() {
            if (this.images.length <= 1) return;
            this.isTransitioning = true;
            setTimeout(() => {
                this.currentImageIndex = (this.currentImageIndex - 1 + this.images.length) % this.images.length;
                this.mainImage = this.images[this.currentImageIndex];
                this.isTransitioning = false;
            }, 150);
        },
    }));
});
</script>
@endpush
