@extends('layouts.admin')

@section('title', 'Nuevo Producto - Synapse Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <div>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Nuevo Producto</h1>
            <p class="text-gray-500 mt-1">Añade un nuevo producto al catálogo.</p>
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

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
          x-data="{
              variantes: [{ color: '', almacenamiento: '', precio: '', stock: '', sku: '' }],
              addVariante() {
                  this.variantes.push({ color: '', almacenamiento: '', precio: '', stock: '', sku: '' });
              },
              removeVariante(index) {
                  if (this.variantes.length > 1) {
                      this.variantes.splice(index, 1);
                  }
              },
              galleryPreviews: [],
              handleGallery(event) {
                  this.galleryPreviews = [];
                  for (const file of event.target.files) {
                      this.galleryPreviews.push(URL.createObjectURL(file));
                  }
              }
          }">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white rounded-3xl shadow-lg p-8 space-y-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <x-icon name="info-circle" class="w-5 h-5 text-[#004689]" />
                Información Básica
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Producto</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                        placeholder="Ej: iPhone 16 Pro Max" />
                </div>

                <div>
                    <label for="id_categoria" class="block text-sm font-semibold text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria" id="id_categoria"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id_categoria }}" {{ old('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="brand" class="block text-sm font-semibold text-gray-700 mb-2">Marca</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition"
                        placeholder="Ej: Apple, Samsung..." />
                </div>

                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                        class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition resize-none"
                        placeholder="Descripción detallada del producto...">{{ old('descripcion') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Variants -->
        <div class="bg-white rounded-3xl shadow-lg p-8 space-y-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <x-icon name="package" class="w-5 h-5 text-[#004689]" />
                    Variantes
                </h2>
                <button type="button" @click="addVariante()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#004689] hover:text-[#002244] transition">
                    <x-icon name="plus" class="w-4 h-4" />
                    Añadir Variante
                </button>
            </div>

            <template x-for="(variante, index) in variantes" :key="index">
                <div class="border border-gray-100 rounded-2xl p-6 space-y-4 relative bg-gray-50/50">
                    <!-- Remove button -->
                    <button type="button" @click="removeVariante(index)" x-show="variantes.length > 1"
                        class="absolute top-3 right-3 p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                        <x-icon name="x-mark" class="w-4 h-4" />
                    </button>

                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider" x-text="'Variante ' + (index + 1)"></p>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Color</label>
                            <input type="text" :name="'variantes[' + index + '][color]'" x-model="variante.color"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition"
                                placeholder="Ej: Black" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Almacenamiento</label>
                            <input type="text" :name="'variantes[' + index + '][almacenamiento]'" x-model="variante.almacenamiento"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition"
                                placeholder="Ej: 256GB" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Precio (€)</label>
                            <input type="number" step="0.01" min="0" :name="'variantes[' + index + '][precio]'" x-model="variante.precio"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition"
                                placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Stock</label>
                            <input type="number" min="0" :name="'variantes[' + index + '][stock]'" x-model="variante.stock"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition"
                                placeholder="0" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">SKU</label>
                            <input type="text" :name="'variantes[' + index + '][sku]'" x-model="variante.sku"
                                class="w-full rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-white border border-gray-200 transition"
                                placeholder="Opcional" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Imagen de Variante</label>
                        <input type="file" :name="'variantes[' + index + '][imagen]'" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#004689]/10 file:text-[#004689] hover:file:bg-[#004689]/20 file:transition file:cursor-pointer" />
                    </div>
                </div>
            </template>
        </div>

        <!-- Gallery Images -->
        <div class="bg-white rounded-3xl shadow-lg p-8 space-y-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <x-icon name="image" class="w-5 h-5 text-[#004689]" />
                Galería de Imágenes
            </h2>

            <div>
                <input type="file" name="imagenes[]" multiple accept="image/*" @change="handleGallery($event)"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#004689]/10 file:text-[#004689] hover:file:bg-[#004689]/20 file:transition file:cursor-pointer" />
                <p class="text-xs text-gray-400 mt-2">JPG, PNG o WebP. Máx. 4 MB por imagen. Puedes seleccionar varias imágenes a la vez.</p>
            </div>

            <!-- Gallery Preview -->
            <div x-show="galleryPreviews.length > 0" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
                <template x-for="(src, i) in galleryPreviews" :key="i">
                    <div class="aspect-square rounded-2xl overflow-hidden border-2 border-gray-100 bg-gray-50">
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
                Crear Producto
            </button>
        </div>
    </form>
</div>
@endsection
