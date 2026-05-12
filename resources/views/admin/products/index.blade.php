@extends('layouts.admin')

@section('title', 'Gestión de Productos - Synapse Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extralight text-gray-900 tracking-tight">Productos</h1>
            <p class="text-gray-500 mt-1">Gestiona el catálogo de productos de la tienda.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nuevo Producto
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o marca..."
                    class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            </div>
            <select name="id_categoria" class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition min-w-[180px]">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id_categoria }}" {{ request('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
                Filtrar
            </button>
            @if(request('search') || request('id_categoria'))
                <a href="{{ route('admin.products.index') }}" class="rounded-full border-2 border-gray-200 text-gray-600 hover:border-gray-400 font-semibold py-3 px-6 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Producto</th>
                        <th class="py-4 px-6">Categoría</th>
                        <th class="py-4 px-6">Precio Base</th>
                        <th class="py-4 px-6">Stock Total</th>
                        <th class="py-4 px-6">Variantes</th>
                        <th class="py-4 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($products as $product)
                        @php
                            $totalStock = $product->variantes->sum('stock');
                            $minPrice   = $product->variantes->min('precio');
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0">
                                        @if($product->variantes->first()?->imagen)
                                            <img src="{{ asset($product->variantes->first()->imagen) }}" alt="{{ $product->nombre }}" class="w-full h-full object-cover" />
                                        @else
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product->nombre }}</p>
                                        @if($product->brand)
                                            <p class="text-xs text-gray-400">{{ $product->brand }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($product->categoria)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">{{ $product->categoria->nombre }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-900">
                                {{ $minPrice !== null ? number_format($minPrice, 2, ',', '.') . '€' : '—' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($totalStock <= 0)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Agotado</span>
                                @elseif($totalStock < 5)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">{{ $totalStock }} uds</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">{{ $totalStock }} uds</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-500">{{ $product->variantes->count() }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}" class="p-2 rounded-xl text-gray-400 hover:text-[#004689] hover:bg-blue-50 transition" title="Ver detalle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-2 rounded-xl text-gray-400 hover:text-[#004689] hover:bg-blue-50 transition" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <div x-data="{ confirmDelete: false }">
                                        <button @click="confirmDelete = true" class="p-2 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>

                                        <!-- Confirm Delete Modal -->
                                        <div x-show="confirmDelete" x-transition x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="confirmDelete = false">
                                            <div class="fixed inset-0 bg-black/50" @click="confirmDelete = false"></div>
                                            <div class="relative bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full z-10">
                                                <div class="text-center">
                                                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    </div>
                                                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar producto?</h3>
                                                    <p class="text-gray-500 mb-2">Estás a punto de eliminar <span class="font-semibold text-gray-900">{{ $product->nombre }}</span> y todas sus variantes.</p>
                                                    <p class="text-gray-400 text-sm mb-4">Esta acción no se puede deshacer.</p>
                                                </div>
                                                <div class="flex gap-3">
                                                    <button @click="confirmDelete = false" class="flex-1 rounded-full border-2 border-gray-200 text-gray-700 font-semibold py-3 px-4 transition hover:border-gray-400 active:scale-95">
                                                        Cancelar
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="flex-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 transition shadow-md active:scale-95">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="text-gray-500 font-medium">No se encontraron productos.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
