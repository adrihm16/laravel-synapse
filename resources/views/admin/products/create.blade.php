@extends('layouts.admin')

@section('title', 'Nuevo Producto - Synapse Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Nuevo Producto</h1>
    </div>

    <!-- Error alerts -->
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="productForm()" class="space-y-6 pb-20">
        @csrf

        <!-- Info básica -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <x-icon name="information-circle" class="w-6 h-6 text-[#004689]" />
                Información Básica
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del producto *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                           class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="4"
                              class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">{{ old('descripcion') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria" class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_categoria }}" {{ old('id_categoria') == $category->id_categoria ? 'selected' : '' }}>
                                {{ $category->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Ej: Apple, Samsung..."
                           class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Base (€) *</label>
                    <input type="number" step="0.01" name="precio_base" x-model="basePrice" required
                           class="w-full md:w-1/3 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition">
                    <p class="text-xs text-gray-500 mt-1">Precio inicial del producto sobre el cual se sumarán los costes extra de cada opción elegida.</p>
                </div>
            </div>
        </div>

        <!-- Groups & Options -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <x-icon name="collection" class="w-6 h-6 text-[#004689]" />
                    Grupos de Opciones
                </h2>
                <button type="button" @click="addGroup" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-full transition active:scale-95">
                    <x-icon name="plus" class="w-4 h-4" />
                    Añadir Grupo
                </button>
            </div>
            
            <p class="text-sm text-gray-500 mb-6">Ej: Color, Almacenamiento, Talla. Añade los valores dentro de cada grupo (Rojo, 128GB, etc.) y luego genera las variantes.</p>

            <div class="space-y-6">
                <template x-for="(group, gIndex) in groups" :key="group.id">
                    <div class="border border-gray-200 rounded-2xl p-6 bg-gray-50 relative">
                        <button type="button" @click="removeGroup(gIndex)" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition" title="Eliminar grupo">
                            <x-icon name="trash" class="w-5 h-5" />
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pr-10">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre del Grupo</label>
                                <input type="text" :name="`grupos[${gIndex}][nombre]`" x-model="group.name" placeholder="Ej: Color" required
                                       class="w-full rounded-xl px-4 py-2 border border-gray-200 focus:ring-2 focus:ring-black outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de UI</label>
                                <select :name="`grupos[${gIndex}][tipo]`" x-model="group.type"
                                        class="w-full rounded-xl px-4 py-2 border border-gray-200 focus:ring-2 focus:ring-black outline-none transition">
                                    <option value="texto">Texto normal (Botones)</option>
                                    <option value="color">Selector de Color</option>
                                </select>
                            </div>
                        </div>

                        <!-- Values -->
                        <div class="pl-4 border-l-2 border-gray-200 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Valores del grupo</h4>
                            
                            <template x-for="(value, vIndex) in group.values" :key="value.id">
                                <div class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="flex-1 min-w-[150px]">
                                        <input type="text" :name="`grupos[${gIndex}][valores][${vIndex}][nombre]`" x-model="value.name" placeholder="Nombre (Ej: Rojo, 128GB)" required
                                               class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black">
                                    </div>
                                    <div class="w-32">
                                        <div class="flex items-center">
                                            <span class="text-gray-500 text-sm mr-1">+</span>
                                            <input type="number" step="0.01" :name="`grupos[${gIndex}][valores][${vIndex}][precio_extra]`" x-model="value.precio_extra" placeholder="0.00"
                                                   class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black text-right">
                                            <span class="text-gray-500 text-sm ml-1">€</span>
                                        </div>
                                    </div>
                                    <template x-if="group.type === 'color'">
                                        <div class="w-24">
                                            <input type="color" :name="`grupos[${gIndex}][valores][${vIndex}][hex_code]`" x-model="value.hex_code"
                                                   class="w-full h-9 rounded cursor-pointer border border-gray-200">
                                        </div>
                                    </template>
                                    <div class="w-auto flex-1 min-w-[200px]">
                                        <input type="file" :name="`grupos[${gIndex}][valores][${vIndex}][imagen]`" accept="image/*"
                                               class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#004689] hover:file:bg-blue-100">
                                    </div>
                                    <button type="button" @click="removeValue(gIndex, vIndex)" class="p-2 text-gray-400 hover:text-red-500" title="Eliminar valor">
                                        <x-icon name="x" class="w-4 h-4" />
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="addValue(gIndex)" class="inline-flex items-center gap-1 mt-2 text-sm text-[#004689] font-medium hover:underline">
                                <x-icon name="plus" class="w-4 h-4" /> Añadir valor
                            </button>
                        </div>
                    </div>
                </template>
                
                <template x-if="groups.length === 0">
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500 font-medium">No has añadido ningún grupo de opciones.</p>
                        <p class="text-gray-400 text-sm mt-1">El producto no tendrá opciones de selección (producto simple).</p>
                    </div>
                </template>
            </div>
            
            <div class="mt-8 flex justify-end pt-6 border-t border-gray-100">
                <button type="button" @click="generateVariants()" class="px-6 py-3 bg-gray-900 hover:bg-black text-white font-semibold rounded-full shadow-md transition active:scale-95 flex items-center gap-2">
                    <x-icon name="refresh" class="w-5 h-5" />
                    Generar Combinaciones (Variantes)
                </button>
            </div>
        </div>

        <!-- Variants Table -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <x-icon name="package" class="w-6 h-6 text-[#004689]" />
                Variantes Generadas
            </h2>
            <p class="text-sm text-gray-500 mb-6">Revisa las combinaciones generadas, ajusta el precio final y el stock disponible.</p>

            <div class="overflow-x-auto border border-gray-100 rounded-2xl">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Opciones</th>
                            <th class="py-3 px-4 w-32">Precio Final (€)</th>
                            <th class="py-3 px-4 w-28">Stock</th>
                            <th class="py-3 px-4 w-40">SKU</th>
                            <th class="py-3 px-4 w-12 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="(variant, vIndex) in variants" :key="variant.id">
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 px-4 font-medium text-gray-900" x-text="variant.names"></td>
                                <td class="py-2 px-4">
                                    <input type="number" step="0.01" :name="`variantes[${vIndex}][precio]`" x-model="variant.price" required
                                           class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black text-right px-2 py-1.5">
                                </td>
                                <td class="py-2 px-4">
                                    <input type="number" :name="`variantes[${vIndex}][stock]`" x-model="variant.stock" required min="0"
                                           class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black text-center px-2 py-1.5">
                                </td>
                                <td class="py-2 px-4">
                                    <input type="text" :name="`variantes[${vIndex}][sku]`" x-model="variant.sku" placeholder="SKU-..."
                                           class="w-full text-sm rounded-lg border-gray-200 focus:ring-black focus:border-black px-2 py-1.5">
                                </td>
                                <td class="py-2 px-4 text-center">
                                    <button type="button" @click="removeVariant(vIndex)" class="text-gray-400 hover:text-red-500 transition">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </td>
                                <td class="hidden">
                                    <!-- Hidden references to option values -->
                                    <template x-for="ref in variant.valRefs">
                                        <input type="hidden" :name="`variantes[${vIndex}][valores][]`" :value="ref">
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <template x-if="variants.length === 0">
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    Haz clic en "Generar Combinaciones" para crear las variantes.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <template x-if="variants.length === 0 && groups.length === 0">
                <div class="mt-4 bg-blue-50 border border-blue-100 p-4 rounded-xl text-sm text-blue-800">
                    <strong>Producto Simple:</strong> Como no has añadido grupos, se creará un producto sin opciones. Debes generar igualmente la variante base pulsando el botón arriba.
                </div>
            </template>
        </div>

        <!-- Galería de imágenes general -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <x-icon name="image" class="w-6 h-6 text-[#004689]" />
                Galería de Imágenes General
            </h2>
            <p class="text-sm text-gray-500 mb-4">Estas imágenes aparecerán en el carrusel principal del producto.</p>
            <input type="file" name="imagenes[]" multiple accept="image/*"
                   class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-blue-50 file:text-[#004689]
                          hover:file:bg-blue-100 transition">
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-4 mt-8 bg-white p-6 rounded-3xl shadow-lg border-t-4 border-[#004689] sticky bottom-4 z-50">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-full text-gray-600 font-medium hover:bg-gray-100 transition">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3 bg-[#004689] hover:bg-[#002244] text-white font-bold rounded-full shadow-lg transition active:scale-95 text-lg">
                Guardar Producto
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productForm', () => ({
        basePrice: 0,
        groups: [
            {
                id: Date.now(),
                name: 'Color',
                type: 'color',
                values: [
                    { id: Date.now() + 1, name: '', hex_code: '#000000', precio_extra: 0 }
                ]
            }
        ],
        variants: [],

        addGroup() {
            this.groups.push({
                id: Date.now(),
                name: '',
                type: 'texto',
                values: [
                    { id: Date.now() + 1, name: '', hex_code: '#000000', precio_extra: 0 }
                ]
            });
        },

        removeGroup(index) {
            this.groups.splice(index, 1);
        },

        addValue(groupIndex) {
            this.groups[groupIndex].values.push({
                id: Date.now(),
                name: '',
                hex_code: '#000000',
                precio_extra: 0
            });
        },

        removeValue(groupIndex, valueIndex) {
            this.groups[groupIndex].values.splice(valueIndex, 1);
        },

        removeVariant(index) {
            this.variants.splice(index, 1);
        },

        generateVariants() {
            if (this.groups.length === 0) {
                // Producto simple
                this.variants = [{
                    id: Date.now(),
                    names: 'Única',
                    price: Number(this.basePrice || 0),
                    stock: 0,
                    sku: '',
                    valRefs: []
                }];
                return;
            }

            // Validar que todos los grupos tengan al menos un valor con nombre
            for (let group of this.groups) {
                let validValues = group.values.filter(v => v.name.trim() !== '');
                if (validValues.length === 0) {
                    alert('Todos los grupos deben tener al menos un valor con nombre para generar combinaciones.');
                    return;
                }
            }

            let combinations = [[]];
            for (let group of this.groups) {
                let validValues = group.values.filter(v => v.name.trim() !== '');
                let temp = [];
                for (let comb of combinations) {
                    for (let val of validValues) {
                        temp.push([...comb, val]);
                    }
                }
                combinations = temp;
            }

            this.variants = combinations.map((comb, index) => {
                let extraPrice = comb.reduce((sum, val) => sum + Number(val.precio_extra || 0), 0);
                let names = comb.map(val => val.name).join(' · ');
                
                // Formato de referencia: indexGrupo_indexValor (necesario para que el backend pueda enlazar los ids)
                let valRefs = comb.map(val => {
                    let gIdx = this.groups.findIndex(g => g.values.some(v => v.id === val.id));
                    let vIdx = this.groups[gIdx].values.findIndex(v => v.id === val.id);
                    return `${gIdx}_${vIdx}`;
                });

                return {
                    id: Date.now() + index,
                    names: names,
                    price: Number(this.basePrice || 0) + extraPrice,
                    stock: 0,
                    sku: '',
                    valRefs: valRefs
                };
            });
        }
    }));
});
</script>
@endpush
