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
        //php artisan db:seed --class=DiscountSeeder
        return [
            'type' => 'percent',
            'value' => random_int(1,30),
            'start_date' => $this->faker->dateTimeBetween('-1month','now'),
            'end_date' => $this->faker->dateTimeBetween('now','+30 days'),
            'is_active' => true,
        ];
    }
}
