<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Variante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variante>
 */
class VarianteFactory extends Factory
{
    protected $model = Variante::class;

    public function definition(): array
    {
        return [
            'id_producto' => Producto::factory(),
            'precio'      => fake()->randomFloat(2, 99, 1999),
            'stock'       => fake()->numberBetween(5, 100),
            'sku'         => fake()->unique()->bothify('SYN-####-??'),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function withStock(int $qty): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $qty,
        ]);
    }
}
