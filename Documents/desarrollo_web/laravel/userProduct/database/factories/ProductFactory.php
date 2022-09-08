<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(1,10),
            'name'=> $this->faker->name(),
            'price' => $this->faker->randomElement(['1500', '6500', '8000', '3000','15000'] )
        ];
    }
}
