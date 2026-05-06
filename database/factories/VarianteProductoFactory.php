<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\VarianteProducto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VarianteProducto>
 */
class VarianteProductoFactory extends Factory
{
    protected $model = VarianteProducto::class;

    public function definition(): array
    {
        return [
            'id_producto'    => Producto::factory(),
            'color'          => fake()->randomElement(['Black', 'White', 'Blue', 'Sand']),
            'almacenamiento' => fake()->randomElement(['128GB', '256GB', '512GB']),
            'precio'         => fake()->randomFloat(2, 99, 1999),
            'stock'          => fake()->numberBetween(5, 100),
            'imagen'         => null,
            'sku'            => fake()->unique()->bothify('SYN-####-??'),
        ];
    }

    /**
     * Indicate the variant has no stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Set a specific stock level.
     */
    public function withStock(int $qty): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $qty,
        ]);
    }
}
