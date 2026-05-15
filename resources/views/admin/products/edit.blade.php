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
                    'imagen_url' => $valor->imagen_url,
                    'deleted' => false,
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
            'valueIds' => $variante->valores->pluck('id_valor')->toArray(),
            'deleted' => false,
        ];
    })->values()->toArray();
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
            <p>Puedes eliminar valores de opciones o variantes individuales usando el botón <strong>&times;</strong> / <strong>papelera</strong>. Eliminar un valor eliminará automáticamente todas las variantes que lo usen. Para añadir o eliminar grupos completos de opciones, crea un producto nuevo.</p>
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

        <!-- Opciones y Valores (Interactivo) -->
        @if(count($existingGroups) > 0)
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <x-icon name="collection" class="w-6 h-6 text-[#004689]" />
                Opciones y Valores
            </h2>
            <p class="text-sm text-gray-500 mb-6">Los valores existentes se muestran en gris. Añade nuevos valores a cada grupo antes de crear variantes que los usen.</p>
            <div class="space-y-5">
                <template x-for="group in groups" :key="group.id_db">
                    <div class="border border-gray-100 rounded-2xl p-5 bg-gray-50/50">
                        <!-- Group header -->
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="font-semibold text-gray-900" x-text="group.name"></h3>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 font-medium capitalize" x-text="group.type"></span>
                        </div>

                        <!-- Existing + pending new values -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <template x-for="val in group.values" :key="val.id_db">
                                <div x-show="!val.deleted" class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 shadow-sm">
                                    <template x-if="group.type === 'color' && val.hex_code">
                                        <span class="w-3 h-3 rounded-full shrink-0" :style="`background-color:${val.hex_code}`"></span>
                                    </template>
                                    <span x-text="val.name"></span>
                                    <span x-show="val.precio_extra > 0" class="text-xs text-gray-400" x-text="`+${val.precio_extra}€`"></span>
                                    <button type="button" @click="deleteExistingValue(group.id_db, val.id_db)"
                                            class="ml-1 text-gray-300 hover:text-red-500 transition font-bold leading-none text-base"
                                            title="Eliminar este valor (y sus variantes)">&times;</button>
                                </div>
                            </template>
                            <template x-for="(val, i) in (pendingNewValues[group.id_db] || [])" :key="i">
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800 shadow-sm">
                                    <template x-if="group.type === 'color' && val.hex_code">
                                        <span class="w-3 h-3 rounded-full shrink-0" :style="`background-color:${val.hex_code}`"></span>
                                    </template>
                                    <span x-text="val.nombre"></span>
                                    <span x-show="val.precio_extra > 0" class="text-xs opacity-60" x-text="`+${val.precio_extra}€`"></span>
                                    <button type="button" @click="removeNewValue(group.id_db, i)"
                                            class="ml-1 text-emerald-500 hover:text-red-500 transition font-bold leading-none text-base"
                                            title="Eliminar este valor pendiente">&times;</button>
                                </div>
                            </template>
                        </div>

                        <!-- Add new value form -->
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Añadir nuevo valor</p>
                            <div class="flex flex-wrap items-end gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Nombre *</label>
                                    <input type="text"
                                           x-model="newValueInputs[group.id_db].nombre"
                                           placeholder="Ej. Verde"
                                           class="rounded-xl border border-gray-200 bg-white focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none px-3 py-2 text-sm w-40">
                                </div>
                                <template x-if="group.type === 'color'">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Color</label>
                                        <input type="color"
                                               x-model="newValueInputs[group.id_db].hex_code"
                                               class="rounded-lg border border-gray-200 w-12 h-9 p-0.5 cursor-pointer">
                                    </div>
                                </template>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Precio extra (€)</label>
                                    <input type="number" step="0.01" min="0"
                                           x-model="newValueInputs[group.id_db].precio_extra"
                                           placeholder="0.00"
                                           class="rounded-xl border border-gray-200 bg-white focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none px-3 py-2 text-sm w-28">
                                </div>
                                <button type="button"
                                        @click="addNewValueToGroup(group.id_db)"
                                        :disabled="!newValueInputs[group.id_db]?.nombre?.trim()"
                                        :class="newValueInputs[group.id_db]?.nombre?.trim()
                                            ? 'bg-emerald-600 hover:bg-emerald-700 text-white'
                                            : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                        class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                                    + Añadir valor
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
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
                            <th class="py-3 px-4 w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="(variant, vIndex) in existingVariants" :key="variant.id">
                            <template x-if="!variant.deleted">
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3 px-4 font-medium text-gray-900" x-text="variant.names"></td>
                                    <td class="py-2 px-4">
                                        <input type="hidden" :name="`variantes_existentes[${vIndex}][id_variante]`" :value="variant.id_db">
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
                                    <td class="py-2 px-4">
                                        <button type="button" @click="deleteExistingVariant(variant.id_db)"
                                                class="text-red-300 hover:text-red-600 transition" title="Eliminar variante">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Añadir Nuevas Variantes -->
        @if(count($existingGroups) > 0)
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <x-icon name="plus-circle" class="w-6 h-6 text-[#004689]" />
                Añadir Nuevas Variantes
            </h2>
            <p class="text-sm text-gray-500 mb-6">Selecciona una opción de cada grupo, rellena precio y stock, y pulsa «Añadir».</p>

            <!-- Selectors per group -->
            <div class="space-y-5 mb-6">
                <template x-for="group in groups" :key="group.id_db">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2" x-text="group.name"></p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="val in allGroupValues(group.id_db)" :key="val.key">
                                <button type="button"
                                    @click="selectValue(group.id_db, val)"
                                    :class="isSelected(group.id_db, val.key)
                                        ? 'bg-[#004689] text-white border-[#004689] shadow'
                                        : 'bg-white text-gray-700 border-gray-200 hover:border-[#004689]'"
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg border text-sm transition">
                                    <template x-if="group.type === 'color' && val.hex_code">
                                        <span class="w-3.5 h-3.5 rounded-full border border-white/40 shrink-0"
                                              :style="`background-color:${val.hex_code}`"></span>
                                    </template>
                                    <span x-text="val.name"></span>
                                    <span x-show="val.precio_extra > 0" class="opacity-70 text-xs"
                                          x-text="`+${val.precio_extra}€`"></span>
                                    <template x-if="val.isNew">
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-medium">nuevo</span>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Price / Stock / SKU row -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Precio final (€) *</label>
                    <input type="number" step="0.01" x-model="newPrice" placeholder="0.00" min="0"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Stock *</label>
                    <input type="number" x-model="newStock" placeholder="0" min="0"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">SKU</label>
                    <input type="text" x-model="newSku" placeholder="Opcional"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none px-3 py-2 text-sm">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="addPending()"
                        :disabled="!allGroupsSelected || !newPrice"
                        :class="allGroupsSelected && newPrice ? 'bg-[#004689] hover:bg-[#002244] text-white' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                        class="w-full py-2 px-4 rounded-xl font-semibold text-sm transition">
                        + Añadir
                    </button>
                </div>
            </div>

            <!-- Pending new variants table -->
            <template x-if="pendingVariants.length > 0">
                <div class="border border-gray-100 rounded-2xl overflow-hidden mt-4">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-4">Combinación</th>
                                <th class="py-3 px-4 w-28">Precio</th>
                                <th class="py-3 px-4 w-24">Stock</th>
                                <th class="py-3 px-4 w-36">SKU</th>
                                <th class="py-3 px-4 w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <template x-for="(v, i) in pendingVariants" :key="i">
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3 px-4 font-medium text-gray-800" x-text="pendingLabel(v)"></td>
                                    <td class="py-3 px-4 text-gray-700" x-text="v.price + ' €'"></td>
                                    <td class="py-3 px-4 text-gray-700" x-text="v.stock"></td>
                                    <td class="py-3 px-4 text-gray-500 text-xs" x-text="v.sku || '—'"></td>
                                    <td class="py-3 px-4">
                                        <button type="button" @click="removePending(i)"
                                                class="text-red-400 hover:text-red-600 transition">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>

            <!-- Hidden inputs: pending new variants -->
            <template x-for="(v, vIdx) in pendingVariants" :key="vIdx">
                <div>
                    <input type="hidden" :name="`variantes_nuevas[${vIdx}][precio]`" :value="v.price">
                    <input type="hidden" :name="`variantes_nuevas[${vIdx}][stock]`"  :value="v.stock">
                    <input type="hidden" :name="`variantes_nuevas[${vIdx}][sku]`"    :value="v.sku">
                    <template x-for="[gId, sel] in Object.entries(v.selections)" :key="gId">
                        <template x-if="!sel.isNew">
                            <input type="hidden" :name="`variantes_nuevas[${vIdx}][valores_existentes][]`" :value="sel.id_db">
                        </template>
                        <template x-if="sel.isNew">
                            <input type="hidden" :name="`variantes_nuevas[${vIdx}][valores_nuevos][]`" :value="sel.key">
                        </template>
                    </template>
                </div>
            </template>

            <!-- Hidden inputs: new option values data -->
            <template x-for="group in groups" :key="group.id_db">
                <template x-for="(val, index) in (pendingNewValues[group.id_db] || [])" :key="index">
                    <div>
                        <input type="hidden" :name="`valores_nuevos[${group.id_db}][${index}][nombre]`"      :value="val.nombre">
                        <input type="hidden" :name="`valores_nuevos[${group.id_db}][${index}][hex_code]`"    :value="val.hex_code || ''">
                        <input type="hidden" :name="`valores_nuevos[${group.id_db}][${index}][precio_extra]`" :value="val.precio_extra || 0">
                    </div>
                </template>
            </template>
        </div>
        @endif

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

        <!-- Galerías por Color -->
        @php
            $colorGroups = $product->gruposOpciones->where('tipo', 'color');
        @endphp
        @if($colorGroups->isNotEmpty())
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <x-icon name="color-swatch" class="w-6 h-6 text-[#004689]" />
                Galería por color
            </h2>
            <p class="text-sm text-gray-500 mb-6">Sube imágenes específicas para cada color. Al seleccionar ese color en la tienda, se mostrará esta galería en lugar de la galería global.</p>

            <div class="space-y-8">
                @foreach($colorGroups as $grupo)
                    @foreach($grupo->valores as $valor)
                        @php $imgs = $colorImages->get($valor->id_valor, collect()); @endphp
                        <div class="border border-gray-100 rounded-2xl p-5 bg-gray-50/50">
                            <div class="flex items-center gap-3 mb-4">
                                @if($valor->hex_code)
                                    <span class="w-5 h-5 rounded-full border border-gray-200 shrink-0" style="background-color: {{ $valor->hex_code }}"></span>
                                @endif
                                <span class="font-semibold text-gray-800">{{ $valor->nombre }}</span>
                                @if($imgs->isNotEmpty())
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium">{{ $imgs->count() }} foto(s)</span>
                                @endif
                            </div>

                            @if($imgs->isNotEmpty())
                                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 gap-3 mb-4">
                                    @foreach($imgs as $img)
                                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-100" x-data="{ deleted: false }" x-show="!deleted">
                                            <img src="{{ $img->url }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-110" />
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click="deleted = true" class="bg-white text-red-500 p-1.5 rounded-full hover:bg-red-50 transition shadow" title="Eliminar">
                                                    <x-icon name="trash" class="w-4 h-4" />
                                                </button>
                                            </div>
                                            <template x-if="deleted">
                                                <input type="hidden" name="eliminar_imagenes[]" value="{{ $img->id_imagen }}">
                                            </template>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <label class="block text-sm font-medium text-gray-600 mb-1">Añadir fotos para <span class="text-gray-900">{{ $valor->nombre }}</span></label>
                            <input type="file" name="galeria_color[{{ $valor->id_valor }}][]" multiple accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#004689] hover:file:bg-blue-100 transition">
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
        @endif

        <!-- Hidden inputs: items to delete -->
        <template x-for="id in valuesToDelete" :key="id">
            <input type="hidden" name="eliminar_valores[]" :value="id">
        </template>
        <template x-for="id in variantsToDelete" :key="id">
            <input type="hidden" name="eliminar_variantes[]" :value="id">
        </template>

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
        groups: {!! json_encode($existingGroups) !!},

        // New variant builder
        selectedValues: {},
        newPrice: '',
        newStock: 0,
        newSku: '',
        pendingVariants: [],

        // New option values (per group)
        pendingNewValues: {},
        newValueInputs: {},

        // Deletions
        valuesToDelete: [],
        variantsToDelete: [],

        init() {
            this.groups.forEach(g => {
                this.newValueInputs[g.id_db] = { nombre: '', hex_code: '#000000', precio_extra: 0 };
                this.pendingNewValues[g.id_db] = [];
            });
        },

        get allGroupsSelected() {
            return this.groups.length > 0 &&
                   Object.keys(this.selectedValues).length === this.groups.length;
        },

        // Returns merged list of existing + pending new values for a group,
        // each with a stable `key` for Alpine tracking and isNew flag.
        allGroupValues(groupId) {
            const group = this.groups.find(g => g.id_db === groupId);
            const existing = (group?.values || [])
                .filter(v => !v.deleted)
                .map(v => ({ ...v, key: `ex_${v.id_db}`, isNew: false }));
            const pending  = (this.pendingNewValues[groupId] || []).map((v, i) => ({
                id_db:       null,
                key:         `nv_${groupId}_${i}`,
                isNew:       true,
                arrayIndex:  i,
                name:        v.nombre,
                hex_code:    v.hex_code,
                precio_extra: v.precio_extra,
            }));
            return [...existing, ...pending];
        },

        selectValue(groupId, val) {
            this.selectedValues[groupId] = val;
        },

        isSelected(groupId, key) {
            return this.selectedValues[groupId]?.key === key;
        },

        addNewValueToGroup(groupId) {
            const input = this.newValueInputs[groupId];
            if (!input?.nombre?.trim()) return;
            const group = this.groups.find(g => g.id_db === groupId);
            this.pendingNewValues[groupId].push({
                nombre:       input.nombre.trim(),
                hex_code:     group?.type === 'color' ? (input.hex_code || null) : null,
                precio_extra: parseFloat(input.precio_extra) || 0,
            });
            this.newValueInputs[groupId] = { nombre: '', hex_code: '#000000', precio_extra: 0 };
        },

        removeNewValue(groupId, index) {
            this.pendingNewValues[groupId].splice(index, 1);
            // Remove pending variants that referenced any new value from this group
            this.pendingVariants = this.pendingVariants.filter(v => !v.selections[groupId]?.isNew);
            if (this.selectedValues[groupId]?.isNew) {
                delete this.selectedValues[groupId];
            }
        },

        addPending() {
            if (!this.allGroupsSelected || !this.newPrice) return;
            this.pendingVariants.push({
                selections: JSON.parse(JSON.stringify(this.selectedValues)),
                price: this.newPrice,
                stock: this.newStock,
                sku:   this.newSku,
            });
            this.selectedValues = {};
            this.newPrice = '';
            this.newStock = 0;
            this.newSku = '';
        },

        removePending(index) {
            this.pendingVariants.splice(index, 1);
        },

        deleteExistingValue(groupId, valIdDb) {
            const group = this.groups.find(g => g.id_db === groupId);
            if (group) {
                const val = group.values.find(v => v.id_db === valIdDb);
                if (val) val.deleted = true;
            }
            if (!this.valuesToDelete.includes(valIdDb)) this.valuesToDelete.push(valIdDb);
            // Cascade: auto-mark variants that use this value for deletion
            this.existingVariants.forEach(v => {
                if (!v.deleted && v.valueIds && v.valueIds.includes(valIdDb)) {
                    v.deleted = true;
                    if (!this.variantsToDelete.includes(v.id_db)) this.variantsToDelete.push(v.id_db);
                }
            });
        },

        deleteExistingVariant(variantIdDb) {
            const variant = this.existingVariants.find(v => v.id_db === variantIdDb);
            if (variant) variant.deleted = true;
            if (!this.variantsToDelete.includes(variantIdDb)) this.variantsToDelete.push(variantIdDb);
        },

        pendingLabel(v) {
            return Object.values(v.selections).map(s => s.name).join(' · ');
        },
    }));
});
</script>
@endpush
