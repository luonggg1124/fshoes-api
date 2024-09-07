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
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->numberBetween(1000, 999999),
            'sale_price' => $this->faker->numberBetween(1000, 999999),
            'description' => $this->faker->text(),
            'short_description' => $this->faker->text(),
            'is_sale' => $this->faker->boolean(),
            'sku' => $this->faker->uuid(),
            'status' => $this->faker->boolean(),
            'stock_qty' => $this->faker->numberBetween(50, 70),
            'qty_sold' => $this->faker->numberBetween(50, 70),
            'image_url' => $this->faker->imageUrl(),
            'image_public_id' => $this->faker->uuid(),

        ];
    }
}
