<?php

namespace Database\Factories;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'name' => fake()->words(2, true), // genere 2 mots aleatoires
        'description' => fake()->sentence(10), // Une phrase de 10 mots
        'price' => fake()->randomFloat(0, 500, 10000), // Prix entre 500 et 1000fcfa
        'is_available' => true,
    ];
}
}
