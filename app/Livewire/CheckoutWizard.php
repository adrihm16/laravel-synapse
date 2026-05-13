<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\DB;

class CheckoutWizard extends Component
{
    public int $step = 1;

    // Shipping fields
    public string $nombreEnvio = '';
    public string $direccion = '';
    public string $ciudad = '';
    public string $codigoPostal = '';
    public string $provincia = '';
    public string $telefono = '';

    // Order result
    public ?int $pedidoId = null;

    public function mount()
    {
        $user = auth()->user();
        $carrito = Carrito::where('id_usuario', $user->id)->get();

        if ($carrito->isEmpty()) {
            return redirect()->route('cart.index');
        }

        // Pre-fill name from user profile
        $this->nombreEnvio = $user->name;
    }

    /**
     * Validation rules for shipping fields.
     */
    protected function rules(): array
    {
        return [
            'nombreEnvio'  => 'required|string|max:150',
            'direccion'    => 'required|string|max:255',
            'ciudad'       => 'required|string|max:100',
            'codigoPostal' => 'required|string|max:10',
            'provincia'    => 'required|string|max:100',
            'telefono'     => 'required|string|max:20',
        ];
    }

    /**
     * Custom validation messages in Spanish.
     */
    protected function messages(): array
    {
        return [
            'nombreEnvio.required'  => 'El nombre es obligatorio.',
            'nombreEnvio.max'       => 'El nombre no puede superar los 150 caracteres.',
            'direccion.required'    => 'La dirección es obligatoria.',
            'direccion.max'         => 'La dirección no puede superar los 255 caracteres.',
            'ciudad.required'       => 'La ciudad es obligatoria.',
            'ciudad.max'            => 'La ciudad no puede superar los 100 caracteres.',
            'codigoPostal.required' => 'El código postal es obligatorio.',
            'codigoPostal.max'      => 'El código postal no puede superar los 10 caracteres.',
            'provincia.required'    => 'La provincia es obligatoria.',
            'provincia.max'         => 'La provincia no puede superar los 100 caracteres.',
            'telefono.required'     => 'El teléfono es obligatorio.',
            'telefono.max'          => 'El teléfono no puede superar los 20 caracteres.',
        ];
    }

    /**
     * Real-time validation as the user types.
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Validate shipping and advance to summary (step 2).
     */
    public function goToSummary()
    {
        $this->validate();
        $this->step = 2;
    }

    /**
     * Return to shipping form preserving entered data.
     */
    public function goBackToShipping()
    {
        $this->step = 1;
    }

    /**
     * Place the order: create Pedido + details, decrement stock, clear cart.
     */
    public function confirm()
    {
        $user = auth()->user();
        $carritoItems = Carrito::with(['variante.producto', 'variante.valores'])
            ->where('id_usuario', $user->id)
            ->get();

        if ($carritoItems->isEmpty()) {
            $this->dispatch('notify', message: 'Tu carrito está vacío.', type: 'error');
            return redirect()->route('cart.index');
        }

        // Stock validation
        foreach ($carritoItems as $item) {
            if ($item->variante->stock < $item->cantidad) {
                $this->dispatch('notify',
                    message: "No hay suficiente stock de {$item->variante->producto->nombre} ({$item->variante->opciones_text}). Disponible: {$item->variante->stock}.",
                    type: 'error'
                );
                return;
            }
        }

        // Calculate total
        $total = $carritoItems->sum(function ($item) {
            return $item->variante->precio * $item->cantidad;
        });

        DB::transaction(function () use ($user, $carritoItems, $total) {
            // Create order
            $pedido = Pedido::create([
                'id_usuario'    => $user->id,
                'fecha'         => now(),
                'total'         => $total,
                'estado'        => 'pagado',
                'nombre_envio'  => $this->nombreEnvio,
                'direccion'     => $this->direccion,
                'ciudad'        => $this->ciudad,
                'codigo_postal' => $this->codigoPostal,
                'provincia'     => $this->provincia,
                'telefono'      => $this->telefono,
            ]);

            // Create order details and decrement stock
            foreach ($carritoItems as $item) {
                DetallePedido::create([
                    'id_pedido'       => $pedido->id_pedido,
                    'id_variante'     => $item->id_variante,
                    'cantidad'        => $item->cantidad,
                    'precio_unitario' => $item->variante->precio,
                ]);

                // Decrement stock
                $item->variante->decrement('stock', $item->cantidad);
            }

            // Clear cart
            Carrito::where('id_usuario', $user->id)->delete();

            $this->pedidoId = $pedido->id_pedido;
        });

        $this->step = 3;
    }

    /**
     * Get cart items with eager-loaded relationships.
     */
    private function getCarritoItems()
    {
        return Carrito::with(['variante.producto', 'variante.valores'])
            ->where('id_usuario', auth()->id())
            ->get();
    }

    /**
     * Get the confirmed order with details (for step 3).
     */
    private function getPedido()
    {
        if (!$this->pedidoId) {
            return null;
        }

        return Pedido::with(['detalles.variante.producto', 'detalles.variante.valores'])
            ->find($this->pedidoId);
    }

    public function render()
    {
        $carrito = $this->step < 3 ? $this->getCarritoItems() : collect();
        $pedido = $this->step === 3 ? $this->getPedido() : null;

        $subtotal = $carrito->sum(fn($item) => $item->variante->precio * $item->cantidad);

        return view('livewire.checkout-wizard', [
            'carrito'  => $carrito,
            'subtotal' => $subtotal,
            'pedido'   => $pedido,
        ]);
    }
}
