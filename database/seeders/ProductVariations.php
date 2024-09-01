<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariations extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::select('id')->get()->pluck('id')->toArray();
        for($i =0 ; $i<5 ; $i++){
            \App\Models\ProductVariations::query()->create(
              [
                'product_id' => fake()->randomElement($products), 
                'sku'=>fake()->word(),
                'price'=>fake()->numberBetween(1,1000),
                'is_sale'=>fake()->randomNumber(1,1),
                'sale_price'=>fake()->numberBetween(1,500),
                'stock_qty'=>fake()->randomNumber(2,99),
                'stock_sold'=>fake()->randomNumber(2,50),
                'image_url'=>fake()->url()
              ]
            );    
        }

    }
}
