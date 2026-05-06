<?php

namespace Tests\Feature;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use App\Models\VarianteProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private VarianteProducto $variante;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $cat = Categoria::factory()->create(['nombre' => 'Smartphones']);
        $prod = Producto::factory()->create(['nombre' => 'Test Phone', 'id_categoria' => $cat->id_categoria]);
        $this->variante = VarianteProducto::factory()->create([
            'id_producto' => $prod->id_producto, 'color' => 'Black',
            'almacenamiento' => '256GB', 'precio' => 999.99, 'stock' => 10,
        ]);
    }

    /** @test */
    public function guest_cannot_access_cart(): void
    {
        $this->get(route('cart.index'))->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_empty_cart(): void
    {
        $r = $this->actingAs($this->user)->get(route('cart.index'));
        $r->assertStatus(200)->assertViewIs('cart.index')->assertViewHas('carrito');
    }

    /** @test */
    public function cart_page_shows_items(): void
    {
        Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 2]);
        $r = $this->actingAs($this->user)->get(route('cart.index'));
        $r->assertStatus(200)->assertViewHas('carrito', fn ($c) => $c->count() === 1 && $c->first()->cantidad === 2);
    }

    /** @test */
    public function user_can_add_product_to_cart(): void
    {
        $r = $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $r->assertRedirect(route('cart.index'))->assertSessionHas('success');
        $this->assertDatabaseHas('carrito', ['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
    }

    /** @test */
    public function adding_same_variant_increments_quantity(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante, 'cantidad' => 2]);
        $item = Carrito::where('id_usuario', $this->user->id)->where('id_variante', $this->variante->id_variante)->first();
        $this->assertEquals(3, $item->cantidad);
    }

    /** @test */
    public function adding_without_cantidad_defaults_to_one(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante]);
        $this->assertDatabaseHas('carrito', ['id_usuario' => $this->user->id, 'cantidad' => 1]);
    }

    /** @test */
    public function adding_invalid_variant_fails_validation(): void
    {
        $r = $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => 99999, 'cantidad' => 1]);
        $r->assertSessionHasErrors('id_variante');
        $this->assertDatabaseCount('carrito', 0);
    }

    /** @test */
    public function adding_without_variant_fails_validation(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), ['cantidad' => 1])->assertSessionHasErrors('id_variante');
    }

    /** @test */
    public function adding_zero_quantity_fails_validation(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante, 'cantidad' => 0])->assertSessionHasErrors('cantidad');
    }

    /** @test */
    public function adding_negative_quantity_fails_validation(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante, 'cantidad' => -1])->assertSessionHasErrors('cantidad');
    }

    /** @test */
    public function user_can_remove_product_from_cart(): void
    {
        $item = Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $r = $this->actingAs($this->user)->post(route('cart.remove'), ['id_carrito' => $item->id_carrito]);
        $r->assertRedirect(route('cart.index'))->assertSessionHas('success');
        $this->assertDatabaseMissing('carrito', ['id_carrito' => $item->id_carrito]);
    }

    /** @test */
    public function user_cannot_remove_another_users_cart_item(): void
    {
        $other = User::factory()->create();
        $item = Carrito::create(['id_usuario' => $other->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $this->actingAs($this->user)->post(route('cart.remove'), ['id_carrito' => $item->id_carrito]);
        $this->assertDatabaseHas('carrito', ['id_carrito' => $item->id_carrito]);
    }

    /** @test */
    public function removing_invalid_cart_id_fails(): void
    {
        $this->actingAs($this->user)->post(route('cart.remove'), ['id_carrito' => 99999])->assertSessionHasErrors('id_carrito');
    }

    /** @test */
    public function user_can_increment_quantity(): void
    {
        $item = Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 2]);
        $this->actingAs($this->user)->post(route('cart.update'), ['id_carrito' => $item->id_carrito, 'action' => 'increment']);
        $this->assertDatabaseHas('carrito', ['id_carrito' => $item->id_carrito, 'cantidad' => 3]);
    }

    /** @test */
    public function user_can_decrement_quantity(): void
    {
        $item = Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 3]);
        $this->actingAs($this->user)->post(route('cart.update'), ['id_carrito' => $item->id_carrito, 'action' => 'decrement']);
        $this->assertDatabaseHas('carrito', ['id_carrito' => $item->id_carrito, 'cantidad' => 2]);
    }

    /** @test */
    public function decrementing_to_zero_removes_item(): void
    {
        $item = Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $r = $this->actingAs($this->user)->post(route('cart.update'), ['id_carrito' => $item->id_carrito, 'action' => 'decrement']);
        $r->assertRedirect(route('cart.index'))->assertSessionHas('success');
        $this->assertDatabaseMissing('carrito', ['id_carrito' => $item->id_carrito]);
    }

    /** @test */
    public function update_with_invalid_action_fails(): void
    {
        $item = Carrito::create(['id_usuario' => $this->user->id, 'id_variante' => $this->variante->id_variante, 'cantidad' => 1]);
        $this->actingAs($this->user)->post(route('cart.update'), ['id_carrito' => $item->id_carrito, 'action' => 'bad'])->assertSessionHasErrors('action');
    }

    /** @test */
    public function guest_cannot_add_to_cart(): void
    {
        $this->post(route('cart.add'), ['id_variante' => $this->variante->id_variante])->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_remove_from_cart(): void
    {
        $this->post(route('cart.remove'), ['id_carrito' => 1])->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_update_cart(): void
    {
        $this->post(route('cart.update'), ['id_carrito' => 1, 'action' => 'increment'])->assertRedirect(route('login'));
    }

    /** @test */
    public function each_user_has_isolated_cart(): void
    {
        $userB = User::factory()->create();
        $this->actingAs($this->user)->post(route('cart.add'), ['id_variante' => $this->variante->id_variante]);
        $r = $this->actingAs($userB)->get(route('cart.index'));
        $r->assertViewHas('carrito', fn ($c) => $c->isEmpty());
        $this->assertDatabaseHas('carrito', ['id_usuario' => $this->user->id]);
    }
}
