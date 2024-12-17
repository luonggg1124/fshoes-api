<?php

namespace Database\Seeders;


use App\Models\Image;
use App\Models\Product;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=ProductSeeder
       
        // Product::factory(50)->create();
        // $allPs = Product::all();
        // foreach ($allPs as $p) {
        //     $p->slug = Str::slug($p->name) . '.' . $p->id;
        //     $p->save();
        // }
        // foreach (Product::query()->take(15)->get() as $product) {
        //     $product->categories()->attach([random_int(4,7),random_int(5,9)]);
        //     for ($i = 0; $i < 3; $i++) {
        //         $image = Image::factory()->create();
        //         DB::table('product_image')->insert([
        //             'product_id' => $product->id,
        //             'image_id' => $image->id,
        //         ]);
        //     }
        // }
        
    }
}
