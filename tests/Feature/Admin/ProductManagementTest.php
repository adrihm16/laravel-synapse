<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\Producto;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_product_persists_groups_values_and_variants_via_service(): void
    {
        $cat = Categoria::factory()->create();
        $service = app(ProductService::class);

        $data = [
            'nombre'       => 'Test Phone',
            'descripcion'  => 'desc',
            'id_categoria' => $cat->id_categoria,
            'brand'        => 'Acme',
            'precio_base'  => 100.00,
            'destacado'    => false,
            'grupos' => [
                [
                    'nombre'  => 'Color',
                    'tipo'    => 'color',
                    'valores' => [
                        ['nombre' => 'Negro', 'hex_code' => '#000000', 'precio_extra' => 0],
                        ['nombre' => 'Blanco', 'hex_code' => '#FFFFFF', 'precio_extra' => 0],
                    ],
                ],
                [
                    'nombre'  => 'Almacenamiento',
                    'tipo'    => 'texto',
                    'valores' => [
                        ['nombre' => '128GB', 'precio_extra' => 0],
                        ['nombre' => '256GB', 'precio_extra' => 50],
                    ],
                ],
            ],
            'variantes' => [
                ['precio' => 800, 'stock' => 5, 'sku' => 'NEG-128', 'valores' => ['0_0', '1_0']],
                ['precio' => 850, 'stock' => 3, 'sku' => 'NEG-256', 'valores' => ['0_0', '1_1']],
                ['precio' => 800, 'stock' => 7, 'sku' => 'BLA-128', 'valores' => ['0_1', '1_0']],
            ],
        ];

        $product = $service->createProduct($data, Request::create('/', 'POST'));

        $this->assertInstanceOf(Producto::class, $product);
        $this->assertDatabaseHas('productos', ['nombre' => 'Test Phone', 'brand' => 'Acme']);
        $this->assertEquals(2, $product->gruposOpciones()->count());
        $this->assertEquals(4, \DB::table('valores_opcion_producto')
            ->whereIn('id_grupo', $product->gruposOpciones->pluck('id_grupo'))
            ->count());
        $this->assertEquals(3, $product->variantes()->count());

        // Each variant should have exactly 2 option values attached (Color + Almacenamiento)
        foreach ($product->variantes as $v) {
            $this->assertEquals(2, $v->valores()->count(), "Variant {$v->sku} should have 2 option values");
        }
    }

    /** @test */
    public function create_product_rolls_back_on_failure(): void
    {
        $cat = Categoria::factory()->create();
        $service = app(ProductService::class);

        $data = [
            'nombre'       => 'Will Fail',
            'id_categoria' => $cat->id_categoria,
            'precio_base'  => 50,
            'grupos' => [
                ['nombre' => 'Color', 'tipo' => 'color', 'valores' => [['nombre' => 'Red']]],
            ],
            'variantes' => [
                // Missing 'precio' / 'stock' triggers a SQL error inside the transaction
                ['sku' => 'BAD'],
            ],
        ];

        try {
            $service->createProduct($data, Request::create('/', 'POST'));
            $this->fail('Expected exception was not thrown');
        } catch (\Throwable) {
            // Expected
        }

        $this->assertDatabaseMissing('productos', ['nombre' => 'Will Fail']);
        $this->assertDatabaseCount('grupos_opcion_producto', 0);
        $this->assertDatabaseCount('valores_opcion_producto', 0);
    }
}
