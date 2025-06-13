<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
     {
         return [
             'description' => $this->faker->sentence(),
             'percentage' => $this->faker->randomFloat(2, 0, 100),
             'state' => $this->faker->boolean(),
         ];
     }
}
