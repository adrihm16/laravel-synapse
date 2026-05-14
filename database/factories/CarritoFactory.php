<?php

namespace Database\Factories;

use App\Models\Carrito;
use App\Models\User;
use App\Models\Variante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carrito>
 */
class CarritoFactory extends Factory
{
    protected $model = Carrito::class;

    public function definition(): array
    {
        return [
            'id_usuario'  => User::factory(),
            'id_variante' => Variante::factory(),
            'cantidad'    => fake()->numberBetween(1, 3),
        ];
    }
}
