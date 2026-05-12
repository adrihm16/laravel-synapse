@extends('layouts.admin')

@section('title', $product->nombre . ' - Synapse Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
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
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
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

    <!-- Variants -->
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Variantes <span class="text-sm font-normal text-gray-400">({{ $product->variantes->count() }})</span>
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Imagen</th>
                        <th class="py-3 px-4">Color</th>
                        <th class="py-3 px-4">Almacenamiento</th>
                        <th class="py-3 px-4">Precio</th>
                        <th class="py-3 px-4">Stock</th>
                        <th class="py-3 px-4">SKU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($product->variantes as $variante)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">
                                @if($variante->imagen)
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-100">
                                        <img src="{{ asset($variante->imagen) }}" class="w-full h-full object-cover" />
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 rounded-full shrink-0 {{ $variante->colorClass }}"></span>
                                    <span class="font-medium text-gray-900">{{ $variante->color }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $variante->almacenamiento }}</td>
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
                <svg class="w-5 h-5 text-[#004689]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Galería <span class="text-sm font-normal text-gray-400">({{ $product->imagenes->count() }})</span>
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($product->imagenes as $imagen)
                    <div class="aspect-square rounded-2xl overflow-hidden border-2 border-gray-100 bg-gray-50">
                        <img src="{{ Storage::url($imagen->ruta) }}" alt="Imagen del producto" class="w-full h-full object-cover" />
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
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Creado</p>
                <p class="font-medium text-gray-900">{{ $product->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
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
