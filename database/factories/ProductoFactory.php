<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre'       => fake()->words(3, true),
            'descripcion'  => fake()->paragraph(),
            'id_categoria' => Categoria::factory(),
            'brand'        => fake()->company(),
        ];
    }
}
