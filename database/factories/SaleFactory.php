<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Coffee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coffee_id' => Coffee::factory()->create()->id,
            'quantity' => fake()->numberBetween(1,100),
            'unit_cost' => fake()->numberBetween(1, 200),
            'selling_price' => fake()->randomFloat()
        ];
    }
}
