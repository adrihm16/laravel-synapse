<div>
    {{-- Step Indicator --}}
    <x-checkout-steps :currentStep="$step" />

    {{-- ==================== STEP 1: SHIPPING FORM ==================== --}}
    @if($step === 1)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Shipping Form --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-3xl p-6 md:p-10 shadow-lg border border-gray-100">
                <h2 class="text-2xl font-light text-gray-900 mb-8">Datos de envío</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre completo --}}
                    <div class="md:col-span-2">
                        <label for="nombreEnvio" class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                        <input
                            type="text"
                            id="nombreEnvio"
                            wire:model.blur="nombreEnvio"
                            placeholder="Nombre y apellidos"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                        />
                        @error('nombreEnvio')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div class="md:col-span-2">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                        <input
                            type="text"
                            id="direccion"
                            wire:model.blur="direccion"
                            placeholder="Calle, número, piso, puerta..."
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                        />
                        @error('direccion')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ciudad --}}
                    <div>
                        <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                        <input
                            type="text"
                            id="ciudad"
                            wire:model.blur="ciudad"
                            placeholder="Madrid"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                        />
                        @error('ciudad')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Código Postal --}}
                    <div>
                        <label for="codigoPostal" class="block text-sm font-medium text-gray-700 mb-2">Código postal</label>
                        <input
                            type="text"
                            id="codigoPostal"
                            wire:model.blur="codigoPostal"
                            placeholder="28001"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                        />
                        @error('codigoPostal')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Provincia --}}
                    <div>
                        <label for="provincia" class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                        <input
                            type="text"
                            id="provincia"
                            wire:model.blur="provincia"
                            placeholder="Madrid"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                        />
                        @error('provincia')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input
                            type="tel"
                            id="telefono"
                            wire:model.blur="telefono"
                            placeholder="+34 600 000 000"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                        />
                        @error('telefono')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Continue button --}}
                <div class="mt-8 flex justify-end">
                    <button
                        wire:click="goToSummary"
                        class="bg-[#004689] text-white font-semibold px-10 py-3.5 rounded-full hover:bg-[#002244] transition shadow-md active:scale-95 transform duration-200"
                    >
                        Continuar
                    </button>
                </div>
            </div>
        </div>

        {{-- Cart Summary Sidebar --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 sticky top-[9.375rem]">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Tu pedido</h3>

                <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                    @foreach($carrito as $item)
                        @php
                            $producto = $item->variante->producto;
                            $imagen = $item->variante->imagen
                                ? asset($item->variante->imagen)
                                : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="h-10 object-contain" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $producto->nombre }}</p>
                                <p class="text-xs text-gray-500">{{ $item->variante->color }} · {{ $item->variante->almacenamiento }}</p>
                                <p class="text-xs text-gray-400">x{{ $item->cantidad }}</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-900 flex-shrink-0">
                                {{ number_format($item->variante->precio * $item->cantidad, 2, ',', '.') }}€
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 mt-6 pt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Subtotal</span>
                        <span>{{ number_format($subtotal, 2, ',', '.') }}€</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 mb-3">
                        <span>Envío</span>
                        <span class="text-green-600 font-medium">Gratis</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <span class="text-base font-bold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($subtotal, 2, ',', '.') }}€</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ==================== STEP 2: ORDER SUMMARY ==================== --}}
    @elseif($step === 2)
    <div class="max-w-4xl mx-auto space-y-8">

        {{-- Shipping Info Card --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-light text-gray-900">Dirección de envío</h2>
                <button
                    wire:click="goBackToShipping"
                    class="text-[#004689] text-sm font-semibold hover:underline transition"
                >
                    Editar
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Nombre</p>
                    <p class="font-medium text-gray-900">{{ $nombreEnvio }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Teléfono</p>
                    <p class="font-medium text-gray-900">{{ $telefono }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-gray-500 mb-1">Dirección</p>
                    <p class="font-medium text-gray-900">{{ $direccion }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Ciudad</p>
                    <p class="font-medium text-gray-900">{{ $ciudad }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Código postal</p>
                    <p class="font-medium text-gray-900">{{ $codigoPostal }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Provincia</p>
                    <p class="font-medium text-gray-900">{{ $provincia }}</p>
                </div>
            </div>
        </div>

        {{-- Items Card --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100">
            <h2 class="text-xl font-light text-gray-900 mb-6">Productos ({{ $carrito->sum('cantidad') }})</h2>

            <div class="divide-y divide-gray-100">
                @foreach($carrito as $item)
                    @php
                        $producto = $item->variante->producto;
                        $imagen = $item->variante->imagen
                            ? asset($item->variante->imagen)
                            : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
                        $lineTotal = $item->variante->precio * $item->cantidad;
                    @endphp
                    <div class="flex items-center gap-4 py-5 first:pt-0 last:pb-0">
                        <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="h-14 object-contain" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900">{{ $producto->nombre }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $item->variante->color }} · {{ $item->variante->almacenamiento }}</p>
                            <p class="text-sm text-gray-400 mt-0.5">Cantidad: {{ $item->cantidad }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-semibold text-gray-900">{{ number_format($lineTotal, 2, ',', '.') }}€</p>
                            @if($item->cantidad > 1)
                                <p class="text-xs text-gray-400 mt-0.5">{{ number_format($item->variante->precio, 2, ',', '.') }}€/ud</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Price Breakdown + Confirm --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100">
            <h2 class="text-xl font-light text-gray-900 mb-6">Resumen del pedido</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal ({{ $carrito->sum('cantidad') }} artículos)</span>
                    <span>{{ number_format($subtotal, 2, ',', '.') }}€</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Envío</span>
                    <span class="text-green-600 font-medium">Gratis</span>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-4 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($subtotal, 2, ',', '.') }}€</span>
                </div>
            </div>

            {{-- Confirm Button with loading state --}}
            <div class="mt-8" x-data="{ loading: false }">
                <button
                    wire:click="confirm"
                    @click="loading = true"
                    x-bind:disabled="loading"
                    class="w-full bg-[#004689] text-white font-semibold py-4 rounded-full hover:bg-[#002244] transition shadow-lg hover:shadow-xl active:scale-95 transform duration-300 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-3"
                >
                    <template x-if="loading">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="loading ? 'Procesando pedido...' : 'Confirmar pedido'"></span>
                </button>
            </div>

            {{-- Back link --}}
            <div class="mt-4 text-center">
                <button
                    wire:click="goBackToShipping"
                    class="text-sm text-gray-500 hover:text-[#004689] transition"
                >
                    ← Volver a los datos de envío
                </button>
            </div>
        </div>

    </div>

    {{-- ==================== STEP 3: CONFIRMATION ==================== --}}
    @elseif($step === 3 && $pedido)
    <div class="max-w-3xl mx-auto">

        {{-- Success Header --}}
        <div class="text-center mb-10">
            {{-- Animated checkmark --}}
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-extralight text-gray-900 mb-2">¡Pedido confirmado!</h1>
            <p class="text-gray-500">Gracias por tu compra. Recibirás un email con los detalles.</p>
            <div class="mt-4 inline-flex items-center gap-2 bg-gray-100 px-5 py-2 rounded-full">
                <span class="text-sm text-gray-500">Nº de pedido</span>
                <span class="text-sm font-bold text-gray-900">SYN-{{ str_pad($pedido->id_pedido, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- Order Details Card --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100 mb-6">
            <h2 class="text-xl font-light text-gray-900 mb-6">Detalles del pedido</h2>

            <div class="divide-y divide-gray-100">
                @foreach($pedido->detalles as $detalle)
                    @php
                        $producto = $detalle->variante->producto;
                        $imagen = $detalle->variante->imagen
                            ? asset($detalle->variante->imagen)
                            : asset('assets/' . str_replace(' ', '', $producto->nombre) . '.png');
                    @endphp
                    <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="h-12 object-contain" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900">{{ $producto->nombre }}</p>
                            <p class="text-sm text-gray-500">{{ $detalle->variante->color }} · {{ $detalle->variante->almacenamiento }}</p>
                            <p class="text-sm text-gray-400">x{{ $detalle->cantidad }}</p>
                        </div>
                        <p class="font-semibold text-gray-900 flex-shrink-0">
                            {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2, ',', '.') }}€
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-100 mt-4 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total pagado</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($pedido->total, 2, ',', '.') }}€</span>
                </div>
            </div>
        </div>

        {{-- Shipping Info Card --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100 mb-8">
            <h2 class="text-xl font-light text-gray-900 mb-4">Envío a</h2>
            <div class="text-sm text-gray-700 space-y-1">
                <p class="font-semibold text-gray-900">{{ $pedido->nombre_envio }}</p>
                <p>{{ $pedido->direccion }}</p>
                <p>{{ $pedido->codigo_postal }} {{ $pedido->ciudad }}, {{ $pedido->provincia }}</p>
                <p>{{ $pedido->telefono }}</p>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a
                href="{{ route('catalog.index') }}"
                class="bg-[#004689] text-white font-semibold px-10 py-3.5 rounded-full hover:bg-[#002244] transition shadow-md active:scale-95 transform duration-200"
            >
                Volver a la tienda
            </a>
            <a
                href="{{ route('home') }}"
                class="bg-white text-gray-700 font-semibold px-10 py-3.5 rounded-full border border-gray-200 hover:bg-gray-50 transition shadow-sm active:scale-95 transform duration-200"
            >
                Ir al inicio
            </a>
        </div>

    </div>
    @endif
</div>
