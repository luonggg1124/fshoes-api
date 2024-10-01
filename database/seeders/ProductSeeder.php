<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=ProductSeeder

        Product::factory(20)->create();
        foreach (Product::all() as $product) {
            DB::table('category_product')->insert([
               'product_id' => $product->id,
               'category_id' => rand(1, 6)
            ]);
            for($i = 0; $i < 3; $i++){
                $image = Image::factory()->create();
                DB::table('product_image')->insert([
                    'product_id' => $product->id,
                    'image_id' => $image->id,
                ]);
            }
        }
    }
}
