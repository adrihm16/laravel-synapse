@extends('layouts.store')
@section('title', 'Dashboard - Synapse')

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 text-center">
                <div class="mb-8">
                    <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full uppercase font-bold tracking-wide mb-4">
                        Estado de cuenta
                    </span>
                    <h1 class="text-3xl md:text-5xl font-light text-gray-900 mb-4 tracking-tight">
                        ¡Hola, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-lg text-gray-500 max-w-lg mx-auto">
                        Has iniciado sesión correctamente. Desde aquí puedes gestionar tu cuenta o volver a la tienda para seguir explorando nuestros productos.
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full bg-white border-2 border-gray-200 text-gray-900 font-semibold py-3 px-8 transition hover:border-gray-400 active:scale-95 text-sm uppercase tracking-wide">
                        Gestionar Perfil
                    </a>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#004689] hover:bg-[#002F5C] text-white font-semibold py-3 px-8 transition shadow-md active:scale-95 text-sm uppercase tracking-wide">
                        Ir a la Tienda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
