@extends('layouts.admin')

@section('title', 'Gestión de Categorías - Synapse Admin')

@section('content')
<div class="space-y-6">
    <x-admin.page-header 
        title="Categorías" 
        description="Gestiona las categorías de productos de la tienda."
    >
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
            <x-icon name="plus" class="w-5 h-5" />
            Nueva Categoría
        </a>
    </x-admin.page-header>

    <x-admin.flash-message />

    <!-- Search -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre..."
                    class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            </div>
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
                Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="rounded-full border-2 border-gray-200 text-gray-600 hover:border-gray-400 font-semibold py-3 px-6 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Imagen</th>
                        <th class="py-4 px-6">Nombre</th>
                        <th class="py-4 px-6">Nº Productos</th>
                        <th class="py-4 px-6">Creada</th>
                        <th class="py-4 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0">
                                    <img src="{{ $category->imagen }}" alt="{{ $category->nombre }}" class="w-full h-full object-cover" />
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-900">{{ $category->nombre }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $category->productos_count > 0 ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    <x-icon name="box" class="w-3.5 h-3.5" />
                                    {{ $category->productos_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-500">{{ $category->created_at ? $category->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 rounded-xl text-gray-400 hover:text-[#004689] hover:bg-blue-50 transition" title="Editar">
                                        <x-icon name="edit" class="w-5 h-5" />
                                    </a>

                                    <x-admin.confirm-delete-modal 
                                        :title="'¿Eliminar categoría?'" 
                                        :action="route('admin.categories.destroy', $category)"
                                    >
                                        <p class="mb-2">Estás a punto de eliminar <span class="font-semibold text-gray-900">{{ $category->nombre }}</span>.</p>
                                        @if($category->productos_count > 0)
                                            <p class="text-amber-600 text-sm font-medium bg-amber-50 rounded-xl px-4 py-2 mt-4">
                                                <x-icon name="exclamation-circle" class="w-4 h-4 inline-block mr-1" />
                                                Esta categoría tiene {{ $category->productos_count }} producto(s). No se puede eliminar.
                                            </p>
                                        @else
                                            <p class="text-gray-400 text-sm">Esta acción no se puede deshacer.</p>
                                        @endif
                                    </x-admin.confirm-delete-modal>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <x-icon name="tag" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                                <p class="text-gray-500 font-medium">No se encontraron categorías.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $categories->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
