<?php

namespace Tests\Feature;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\User;
use App\Models\VarianteProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\CheckoutWizard;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private VarianteProducto $variante;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['name' => 'Elena Test']);
        $cat = Categoria::factory()->create(['nombre' => 'Smartphones']);
        $prod = Producto::factory()->create(['nombre' => 'Phone X', 'id_categoria' => $cat->id_categoria]);
        $this->variante = VarianteProducto::factory()->create([
            'id_producto' => $prod->id_producto, 'color' => 'Black',
            'almacenamiento' => '256GB', 'precio' => 799.99, 'stock' => 10,
        ]);
    }

    // ── Checkout page access ──────────────────────────────

    /** @test */
    public function guest_cannot_access_checkout(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
    }

    /** @test */
    public function checkout_redirects_when_cart_is_empty(): void
    {
        $r = $this->actingAs($this->user)->get(route('checkout.index'));
        $r->assertRedirect(route('cart.index'));
    }

    /** @test */
    public function checkout_loads_when_cart_has_items(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $r = $this->actingAs($this->user)->get(route('checkout.index'));
        $r->assertStatus(200);
    }

    // ── Livewire wizard: shipping validation ─────────────

    /** @test */
    public function checkout_requires_all_shipping_fields(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', '')
            ->set('direccion', '')
            ->set('ciudad', '')
            ->set('codigoPostal', '')
            ->set('provincia', '')
            ->set('telefono', '')
            ->call('goToSummary')
            ->assertHasErrors(['direccion', 'ciudad', 'codigoPostal', 'provincia', 'telefono']);
    }

    /** @test */
    public function checkout_advances_to_summary_with_valid_shipping(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena Test')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->assertHasNoErrors()
            ->assertSet('step', 2);
    }

    /** @test */
    public function checkout_can_go_back_to_shipping(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena Test')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->call('goBackToShipping')
            ->assertSet('step', 1)
            ->assertSet('direccion', 'Calle Mayor 1');
    }

    // ── Livewire wizard: confirm order ───────────────────

    /** @test */
    public function checkout_confirm_creates_order_and_clears_cart(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 2]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena Test')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->call('confirm')
            ->assertSet('step', 3);

        // Order created
        $this->assertDatabaseCount('pedidos', 1);
        $pedido = Pedido::first();
        $this->assertEquals($this->user->id, $pedido->id_usuario);
        $this->assertEquals('pagado', $pedido->estado);
        $this->assertEquals(799.99 * 2, (float) $pedido->total);
        $this->assertEquals('Madrid', $pedido->ciudad);

        // Order detail created
        $this->assertDatabaseCount('detalle_pedido', 1);
        $detalle = DetallePedido::first();
        $this->assertEquals(2, $detalle->cantidad);
        $this->assertEquals(799.99, (float) $detalle->precio_unitario);

        // Stock decremented
        $this->variante->refresh();
        $this->assertEquals(8, $this->variante->stock);

        // Cart cleared
        $this->assertDatabaseCount('carrito', 0);
    }

    // ── Error case: insufficient stock ───────────────────

    /** @test */
    public function checkout_fails_when_stock_is_insufficient(): void
    {
        // Request 5 but only 2 in stock
        $this->variante->update(['stock' => 2]);
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 5]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena Test')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->call('confirm')
            ->assertDispatched('notify');

        // No order should have been created
        $this->assertDatabaseCount('pedidos', 0);
        // Stock should remain untouched
        $this->variante->refresh();
        $this->assertEquals(2, $this->variante->stock);
        // Cart still has the item
        $this->assertDatabaseCount('carrito', 1);
    }

    /** @test */
    public function checkout_fails_when_stock_is_zero(): void
    {
        $this->variante->update(['stock' => 0]);
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena Test')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->call('confirm')
            ->assertDispatched('notify');

        $this->assertDatabaseCount('pedidos', 0);
    }

    // ── Error case: incomplete shipping data ─────────────

    /** @test */
    public function checkout_rejects_missing_direccion(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena')
            ->set('direccion', '')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->assertHasErrors(['direccion']);
    }

    /** @test */
    public function checkout_rejects_missing_telefono(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '')
            ->call('goToSummary')
            ->assertHasErrors(['telefono']);
    }

    /** @test */
    public function checkout_rejects_missing_codigo_postal(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->assertHasErrors(['codigoPostal']);
    }

    // ── Multiple products in order ───────────────────────

    /** @test */
    public function checkout_handles_multiple_cart_items(): void
    {
        $cat = Categoria::first();
        $prod2 = Producto::factory()->create(['nombre' => 'Tablet Y', 'id_categoria' => $cat->id_categoria]);
        $v2 = VarianteProducto::factory()->create([
            'id_producto' => $prod2->id_producto, 'color' => 'White',
            'almacenamiento' => '128GB', 'precio' => 499.99, 'stock' => 5,
        ]);

        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $v2->id_variante, 'cantidad' => 2]);

        Livewire::actingAs($this->user)
            ->test(CheckoutWizard::class)
            ->set('nombreEnvio', 'Elena Test')
            ->set('direccion', 'Calle Mayor 1')
            ->set('ciudad', 'Madrid')
            ->set('codigoPostal', '28001')
            ->set('provincia', 'Madrid')
            ->set('telefono', '612345678')
            ->call('goToSummary')
            ->call('confirm')
            ->assertSet('step', 3);

        $this->assertDatabaseCount('pedidos', 1);
        $this->assertDatabaseCount('detalle_pedido', 2);

        $pedido = Pedido::first();
        $expectedTotal = 799.99 + (499.99 * 2);
        $this->assertEquals($expectedTotal, (float) $pedido->total);

        $this->variante->refresh();
        $v2->refresh();
        $this->assertEquals(9, $this->variante->stock);
        $this->assertEquals(3, $v2->stock);
    }

    // ── Pedido referencia attribute ──────────────────────

    /** @test */
    public function pedido_generates_correct_referencia(): void
    {
        $pedido = Pedido::create([
            'id_usuario' => $this->user->id,
            'fecha' => now(),
            'total' => 100,
            'estado' => 'pendiente',
        ]);

        $this->assertStringStartsWith('SYN-', $pedido->referencia);
        $this->assertEquals(10, strlen($pedido->referencia));
    }
}
