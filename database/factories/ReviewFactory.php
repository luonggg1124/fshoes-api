<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => random_int(1,50),
            'user_id' => random_int(1,5),
            'title' => $this->faker->sentence,
            'text' => $this->faker->paragraph,
            'rating' => random_int(3,5)
        ];
    }
}
