@extends('layouts.admin')

@section('title', 'Editar Producto - Synapse Admin')

@section('content')
@php
    // Pre-process existing data to load into Alpine JS
    $existingGroups = $product->gruposOpciones->map(function($grupo) {
        return [
            'id_db' => $grupo->id_grupo,
            'id' => 'g_' . $grupo->id_grupo,
            'name' => $grupo->nombre,
            'type' => $grupo->tipo,
            'values' => $grupo->valores->map(function($valor) {
                return [
                    'id_db' => $valor->id_valor,
                    'id' => 'v_' . $valor->id_valor,
                    'name' => $valor->nombre,
                    'hex_code' => $valor->hex_code ?? '#000000',
                    'precio_extra' => $valor->precio_extra,
                    'imagen_url' => $valor->imagen_url
                ];
            })->values()->toArray()
        ];
    })->values()->toArray();

    $existingVariants = $product->variantes->map(function($variante) {
        return [
            'id_db' => $variante->id_variante,
            'id' => 'var_' . $variante->id_variante,
            'names' => $variante->opciones_text,
            'price' => $variante->precio,
            'stock' => $variante->stock,
            'sku' => $variante->sku ?? '',
            'valRefs' => [] // existing variants don't need valRefs re-linking for update if not modified structurally
        ];
    })->values()->toArray();
    
    // Note: To truly support full EAV editing (modifying existing groups/values and their relation to variants),
    // it requires complex sync logic. The ProductService `updateProduct` currently wipes variants if
    // the structure changes. To keep it simple, we inform the user that structural changes require recreating variants.
@endphp

<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Editar: {{ $product->nombre }}</h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm font-medium mb-6">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="exclamation-triangle" class="w-5 h-5 text-red-500" />
                <span class="font-bold">Por favor, corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 ml-7">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl text-sm font-medium mb-6">
        <div class="flex gap-2">
            <x-icon name="information-circle" class="w-5 h-5 text-blue-500 shrink-0" />
            <p>Debido a la nueva arquitectura, si deseas añadir o eliminar grupos de opciones, se recomienda crear un producto nuevo. Para modificar precios, stock o información básica de este producto, puedes hacerlo a continuación. Si re-generas las variantes, las variantes anteriores se reemplazarán.</p>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" 
          x-data="productEditForm()" class="space-y-6 pb-20">
        @csrf
        @method('PUT')

        <!-- Info básica -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <x-icon name="information-circle" class="w-6 h-6 text-[#004689]" />
                Información Básica
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del producto *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $product->nombre) }}" required
                           class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="4"
                              class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">{{ old('descripcion', $product->descripcion) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria" class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_categoria }}" {{ old('id_categoria', $product->id_categoria) == $category->id_categoria ? 'selected' : '' }}>
                                {{ $category->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}"
                           class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Base (€) *</label>
                    <input type="number" step="0.01" name="precio_base" x-model="basePrice" required
                           class="w-full md:w-1/3 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                </div>
            </div>
        </div>

        <!-- Opciones (Solo lectura simplificada para edición) -->
        @if(count($existingGroups) > 0)
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <x-icon name="collection" class="w-6 h-6 text-[#004689]" />
                Opciones actuales
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($product->gruposOpciones as $grupo)
                    <div class="border border-gray-100 rounded-2xl p-5 bg-gray-50/50">
                        <h3 class="font-semibold text-gray-900 mb-3">{{ $grupo->nombre }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($grupo->valores as $valor)
                                <div class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 shadow-sm flex items-center gap-2">
                                    @if($valor->hex_code)
                                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $valor->hex_code }}"></span>
                                    @endif
                                    {{ $valor->nombre }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Variantes Existentes -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <x-icon name="package" class="w-6 h-6 text-[#004689]" />
                Variantes Existentes
            </h2>
            
            <div class="overflow-x-auto border border-gray-100 rounded-2xl mt-6">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Opciones</th>
                            <th class="py-3 px-4 w-32">Precio Final (€)</th>
                            <th class="py-3 px-4 w-28">Stock</th>
                            <th class="py-3 px-4 w-40">SKU</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="(variant, vIndex) in existingVariants" :key="variant.id">
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 px-4 font-medium text-gray-900" x-text="variant.names"></td>
                                <td class="py-2 px-4">
                                    <!-- Campos marcados como obsoletos, pero retenidos para update básico -->
                                    <input type="hidden" :name="`variantes_existentes[${vIndex}][id_variante]`" :value="variant.id_db">
                                    <input type="hidden" :name="`variantes_existentes[${vIndex}][color]`" value="N/A">
                                    <input type="hidden" :name="`variantes_existentes[${vIndex}][almacenamiento]`" value="N/A">
                                    
                                    <input type="number" step="0.01" :name="`variantes_existentes[${vIndex}][precio]`" x-model="variant.price" required
                                           class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black text-right px-2 py-1.5">
                                </td>
                                <td class="py-2 px-4">
                                    <input type="number" :name="`variantes_existentes[${vIndex}][stock]`" x-model="variant.stock" required min="0"
                                           class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black text-center px-2 py-1.5">
                                </td>
                                <td class="py-2 px-4">
                                    <input type="text" :name="`variantes_existentes[${vIndex}][sku]`" x-model="variant.sku"
                                           class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black px-2 py-1.5">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Galería de Imágenes -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <x-icon name="image" class="w-6 h-6 text-[#004689]" />
                Galería de Imágenes
            </h2>
            
            @if($product->imagenes->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4 mb-8">
                    @foreach($product->imagenes as $imagen)
                        <div class="relative group aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50" x-data="{ deleted: false }" x-show="!deleted">
                            <img src="{{ $imagen->url }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" @click="deleted = true" class="bg-white text-red-500 p-2 rounded-full hover:bg-red-50 transition shadow-lg" title="Eliminar imagen">
                                    <x-icon name="trash" class="w-5 h-5" />
                                </button>
                            </div>
                            <template x-if="deleted">
                                <input type="hidden" name="eliminar_imagenes[]" value="{{ $imagen->id_imagen }}">
                            </template>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 mb-8 bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-6 text-center">No hay imágenes en la galería.</p>
            @endif

            <label class="block text-sm font-medium text-gray-700 mb-2">Añadir nuevas imágenes</label>
            <input type="file" name="imagenes[]" multiple accept="image/*"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#004689] hover:file:bg-blue-100 transition">
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-4 mt-8 bg-white p-6 rounded-3xl shadow-lg border-t-4 border-[#004689] sticky bottom-4 z-50">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-full text-gray-600 font-medium hover:bg-gray-100 transition">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3 bg-[#004689] hover:bg-[#002244] text-white font-bold rounded-full shadow-lg transition active:scale-95 text-lg">
                Actualizar Producto
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productEditForm', () => ({
        basePrice: {{ $product->precio_base }},
        existingVariants: {!! json_encode($existingVariants) !!},
    }));
});
</script>
@endpush
