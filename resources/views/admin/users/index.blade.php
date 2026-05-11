@extends('layouts.admin')

@section('title', 'Gestión de Usuarios - Synapse Admin')

@section('content')
<div class="space-y-6">
    <x-admin.page-header 
        title="Usuarios" 
        description="Gestiona los usuarios registrados en la plataforma."
    >
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
            <x-icon name="plus" class="w-5 h-5" />
            Nuevo Usuario
        </a>
    </x-admin.page-header>

    <x-admin.flash-message />

    <!-- Filters Card -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o email..."
                    class="w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition" />
            </div>
            <select name="rol" class="rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white border border-gray-200 transition min-w-[160px]">
                <option value="">Todos los roles</option>
                <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="cliente" {{ request('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
            </select>
            <button type="submit" class="rounded-full bg-[#004689] hover:bg-[#002244] text-white font-semibold py-3 px-6 transition shadow-md active:scale-95">
                Filtrar
            </button>
            @if(request('search') || request('rol'))
                <a href="{{ route('admin.users.index') }}" class="rounded-full border-2 border-gray-200 text-gray-600 hover:border-gray-400 font-semibold py-3 px-6 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Usuario</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Rol</th>
                        <th class="py-4 px-6">Registro</th>
                        <th class="py-4 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-[#004689] to-[#002F5C] rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-600">{{ $user->email }}</td>
                            <td class="py-4 px-6">
                                @if($user->rol === 'admin')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">Admin</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-600">Cliente</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-500">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-xl text-gray-400 hover:text-[#004689] hover:bg-blue-50 transition" title="Editar">
                                        <x-icon name="edit" class="w-5 h-5" />
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <x-admin.confirm-delete-modal 
                                            :title="'¿Eliminar usuario?'" 
                                            :action="route('admin.users.destroy', $user)"
                                        >
                                            <p>Estás a punto de eliminar a <span class="font-semibold text-gray-900">{{ $user->name }}</span>. Esta acción no se puede deshacer.</p>
                                        </x-admin.confirm-delete-modal>
                                    @else
                                        <span class="p-2 rounded-xl text-gray-200 cursor-not-allowed" title="No puedes eliminarte a ti mismo">
                                            <x-icon name="trash" class="w-5 h-5" />
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <x-icon name="users" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                                <p class="text-gray-500 font-medium">No se encontraron usuarios.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
