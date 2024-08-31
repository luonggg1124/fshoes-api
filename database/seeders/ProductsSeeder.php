<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::select('id')->get()->pluck('id')->toArray();
        for($i =0 ; $i<5 ; $i++){
            Product::query()->create(
              [
                'category_id' => fake()->randomElement($categories), 
                'name'=>fake()->name(),
                'slug'=>fake()->slug(),
                'price'=>fake()->numberBetween(1,1000),
                'sale_price'=>fake()->numberBetween(1,500),
                'is_sale'=>fake()->randomNumber(1 ,1),
                'short_description'=>fake()->sentence(),
                'description'=>fake()->sentence(),
                'sku'=>fake()->word(),
                'status'=>fake()->randomNumber(1 ,1),
                'stock_qty'=>fake()->randomNumber(2,99)
              ]
            );    
        }

    }
}
