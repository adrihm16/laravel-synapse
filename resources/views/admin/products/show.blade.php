@extends('layouts.admin')

@section('title', $product->nombre . ' - Synapse Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
                <x-icon name="arrow-left" class="w-5 h-5" />
            </a>
            <div>
                <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">{{ $product->nombre }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    @if($product->brand)
                        <span class="text-gray-500">{{ $product->brand }}</span>
                    @endif
                    @if($product->categoria)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">{{ $product->categoria->nombre }}</span>
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-2 rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
            <x-icon name="edit" class="w-5 h-5" />
            Editar Producto
        </a>
    </div>

    <!-- Description -->
    @if($product->descripcion)
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Descripción</h2>
            <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $product->descripcion }}</p>
        </div>
    @endif

    <!-- Option Groups -->
    @if($product->gruposOpciones->count() > 0)
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <x-icon name="collection" class="w-5 h-5 text-[#004689]" />
            Atributos y Opciones
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($product->gruposOpciones as $grupo)
                <div class="border border-gray-100 rounded-2xl p-5 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-900 mb-3">{{ $grupo->nombre }} <span class="text-xs text-gray-400 font-normal ml-2">({{ $grupo->tipo }})</span></h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($grupo->valores as $valor)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm shadow-sm">
                                @if($valor->imagen_url)
                                    <img src="{{ $valor->imagen_url }}" class="w-5 h-5 rounded object-cover" />
                                @endif
                                @if($valor->hex_code)
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $valor->hex_code }}"></span>
                                @endif
                                <span class="font-medium text-gray-700">{{ $valor->nombre }}</span>
                                @if($valor->precio_extra > 0)
                                    <span class="text-xs text-green-600 font-semibold">+{{ number_format($valor->precio_extra, 2, ',', '.') }}€</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Variants -->
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <x-icon name="package" class="w-5 h-5 text-[#004689]" />
            Variantes <span class="text-sm font-normal text-gray-400">({{ $product->variantes->count() }})</span>
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Opciones</th>
                        <th class="py-3 px-4">Precio Total</th>
                        <th class="py-3 px-4">Stock</th>
                        <th class="py-3 px-4">SKU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($product->variantes as $variante)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-900">{{ $variante->opciones_text }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-gray-900">{{ number_format($variante->precio, 2, ',', '.') }}€</td>
                            <td class="py-3 px-4">
                                @if($variante->stock <= 0)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Agotado</span>
                                @elseif($variante->stock < 5)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">{{ $variante->stock }}</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">{{ $variante->stock }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-400 font-mono text-xs">{{ $variante->sku ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gallery -->
    @if($product->imagenes->count() > 0)
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <x-icon name="image" class="w-5 h-5 text-[#004689]" />
                Galería <span class="text-sm font-normal text-gray-400">({{ $product->imagenes->count() }})</span>
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($product->imagenes as $imagen)
                    <div class="aspect-square rounded-2xl overflow-hidden border-2 border-gray-100 bg-gray-50">
                        <img src="{{ $imagen->url }}" alt="Imagen del producto" class="w-full h-full object-cover" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Meta info -->
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">ID</p>
                <p class="font-mono font-medium text-gray-900">#{{ $product->id_producto }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Precio Base</p>
                <p class="font-medium text-gray-900">{{ number_format($product->precio_base, 2, ',', '.') }}€</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Actualizado</p>
                <p class="font-medium text-gray-900">{{ $product->updated_at?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Stock Total</p>
                <p class="font-bold text-gray-900">{{ $product->variantes->sum('stock') }} uds</p>
            </div>
        </div>
    </div>
</div>
@endsection
