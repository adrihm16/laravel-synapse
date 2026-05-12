@extends('layouts.admin')

@section('title', 'Editar Producto - Synapse Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Editar Producto</h1>
            <p class="text-gray-500 mt-1">Modifica los datos de <span class="font-semibold text-gray-900">{{ $product->nombre }}</span>.</p>
        </div>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm">
            <p class="font-semibold mb-2">Por favor, corrige los siguientes errores:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
          x-data="{
              nuevasVariantes: [],
              addNuevaVariante() {
                  this.nuevasVariantes.push({ color: '', almacenamiento: '', precio: '', stock: '', sku: '' });
              },
              removeNuevaVariante(index) {
                  this.nuevasVariantes.splice(index, 1);
              },
              variantesAEliminar: [],
              marcarEliminar(id) {
                  this.variantesAEliminar.push(id);
              },
              galleryPreviews: [],
              imagenesAEliminar: [],
              handleGallery(event) {
                  this.galleryPreviews = [];
                  for (const file of event.target.files) {
                      this.galleryPreviews.push(URL.createObjectURL(file));
                  }
              },
              marcarEliminarImagen(id) {
                  this.imagenesAEliminar.push(id);
              }
          }">
        @csrf
        @method('PUT')

        <!-- Hidden fields for deletions -->
        <template x-for="id in variantesAEliminar" :key="'del-v-' + id">
            <input type="hidden" name="eliminar_variantes[]" :value="id" />
        </template>
        <template x-for="id in imagenesAEliminar" :key="'del-i-' + id">
            <input type="hidden" name="eliminar_imagenes[]" :value="id" />
        </template>

        <!-- Basic Info -->
        <div class="bg-white rounded-3xl shadow-lg p-8 space-y-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Información Básica
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Producto</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $product->nombre) }}"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
                </div>

                <div>
                    <label for="id_categoria" class="block text-sm font-semibold text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria" id="id_categoria"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id_categoria }}" {{ old('id_categoria', $product->id_categoria) == $cat->id_categoria ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="brand" class="block text-sm font-semibold text-gray-700 mb-2">Marca</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
                </div>

                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition resize-none">{{ old('descripcion', $product->descripcion) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Existing Variants -->
        <div class="bg-white rounded-3xl shadow-lg p-8 space-y-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Variantes
                </h2>
                <button type="button" @click="addNuevaVariante()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#004689] hover:text-[#002244] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Añadir Variante
                </button>
            </div>

            <!-- Existing variants from DB -->
            @foreach($product->variantes as $vIndex => $variante)
                <div x-show="!variantesAEliminar.includes({{ $variante->id_variante }})"
                     class="border border-gray-100 rounded-2xl p-6 space-y-4 relative bg-gray-50/50">
                    <input type="hidden" name="variantes_existentes[{{ $vIndex }}][id_variante]" value="{{ $variante->id_variante }}" />

                    <!-- Remove button -->
                    <button type="button" @click="marcarEliminar({{ $variante->id_variante }})"
                        class="absolute top-3 right-3 p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="flex items-center gap-2">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Variante {{ $vIndex + 1 }}</p>
                        @if($variante->imagen)
                            <div class="w-8 h-8 rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ asset($variante->imagen) }}" class="w-full h-full object-cover" />
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Color</label>
                            <input type="text" name="variantes_existentes[{{ $vIndex }}][color]" value="{{ old("variantes_existentes.{$vIndex}.color", $variante->color) }}"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Almacenamiento</label>
                            <input type="text" name="variantes_existentes[{{ $vIndex }}][almacenamiento]" value="{{ old("variantes_existentes.{$vIndex}.almacenamiento", $variante->almacenamiento) }}"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Precio (€)</label>
                            <input type="number" step="0.01" min="0" name="variantes_existentes[{{ $vIndex }}][precio]" value="{{ old("variantes_existentes.{$vIndex}.precio", $variante->precio) }}"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Stock</label>
                            <input type="number" min="0" name="variantes_existentes[{{ $vIndex }}][stock]" value="{{ old("variantes_existentes.{$vIndex}.stock", $variante->stock) }}"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">SKU</label>
                            <input type="text" name="variantes_existentes[{{ $vIndex }}][sku]" value="{{ old("variantes_existentes.{$vIndex}.sku", $variante->sku) }}"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Reemplazar Imagen</label>
                        <input type="file" name="variantes_existentes[{{ $vIndex }}][imagen]" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#004689]/10 file:text-[#004689] hover:file:bg-[#004689]/20 file:transition file:cursor-pointer" />
                    </div>
                </div>
            @endforeach

            <!-- New variants (Alpine.js dynamic) -->
            <template x-for="(variante, index) in nuevasVariantes" :key="'new-' + index">
                <div class="border-2 border-dashed border-[#004689]/20 rounded-2xl p-6 space-y-4 relative bg-blue-50/30">
                    <button type="button" @click="removeNuevaVariante(index)"
                        class="absolute top-3 right-3 p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <p class="text-xs font-medium text-[#004689] uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Nueva Variante
                    </p>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Color</label>
                            <input type="text" :name="'variantes_nuevas[' + index + '][color]'" x-model="variante.color"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" placeholder="Ej: Black" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Almacenamiento</label>
                            <input type="text" :name="'variantes_nuevas[' + index + '][almacenamiento]'" x-model="variante.almacenamiento"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" placeholder="Ej: 256GB" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Precio (€)</label>
                            <input type="number" step="0.01" min="0" :name="'variantes_nuevas[' + index + '][precio]'" x-model="variante.precio"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Stock</label>
                            <input type="number" min="0" :name="'variantes_nuevas[' + index + '][stock]'" x-model="variante.stock"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" placeholder="0" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">SKU</label>
                            <input type="text" :name="'variantes_nuevas[' + index + '][sku]'" x-model="variante.sku"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition" placeholder="Opcional" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Imagen de Variante</label>
                        <input type="file" :name="'variantes_nuevas[' + index + '][imagen]'" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#004689]/10 file:text-[#004689] hover:file:bg-[#004689]/20 file:transition file:cursor-pointer" />
                    </div>
                </div>
            </template>
        </div>

        <!-- Gallery Images -->
        <div class="bg-white rounded-3xl shadow-lg p-8 space-y-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Galería de Imágenes
            </h2>

            <!-- Existing images -->
            @if($product->imagenes->count() > 0)
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    @foreach($product->imagenes as $imagen)
                        <div x-show="!imagenesAEliminar.includes({{ $imagen->id_imagen }})" class="relative group aspect-square rounded-2xl overflow-hidden border-2 border-gray-100 bg-gray-50">
                            <img src="{{ Storage::url($imagen->ruta) }}" class="w-full h-full object-cover" />
                            <button type="button" @click="marcarEliminarImagen({{ $imagen->id_imagen }})"
                                class="absolute top-1.5 right-1.5 p-1 rounded-full bg-red-600 text-white opacity-0 group-hover:opacity-100 transition shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- New images upload -->
            <div>
                <input type="file" name="imagenes[]" multiple accept="image/*" @change="handleGallery($event)"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#004689]/10 file:text-[#004689] hover:file:bg-[#004689]/20 file:transition file:cursor-pointer" />
                <p class="text-xs text-gray-400 mt-2">Añadir más imágenes a la galería. JPG, PNG o WebP. Máx. 4 MB.</p>
            </div>

            <!-- New images preview -->
            <div x-show="galleryPreviews.length > 0" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
                <template x-for="(src, i) in galleryPreviews" :key="'gp-' + i">
                    <div class="aspect-square rounded-2xl overflow-hidden border-2 border-dashed border-[#004689]/30 bg-blue-50/30">
                        <img :src="src" class="w-full h-full object-cover" />
                    </div>
                </template>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" class="rounded-full border-2 border-gray-200 text-gray-700 font-semibold py-3 px-8 transition hover:border-gray-400 active:scale-95">
                Cancelar
            </a>
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-8 transition shadow-md active:scale-95">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
