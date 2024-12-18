<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
       
        Product::factory(50)->create();
        $allPs = Product::all();
        foreach ($allPs as $p) {
            $p->slug = Str::slug($p->name) . '.' . $p->id;
            $p->save();
        }
        foreach (Product::query()->take(15)->get() as $product) {
            DB::table('category_product')->insert([
                'product_id' => $product->id,
                'category_id' => rand(1, 4)
            ]);
            for ($i = 0; $i < 3; $i++) {
                $image = Image::factory()->create();
                DB::table('product_image')->insert([
                    'product_id' => $product->id,
                    'image_id' => $image->id,
                ]);
            }
        }
        $trendThisWeek = Category::query()->where('name', 'Trend This Week')->first();
        $weekProducts = Product::query()->take(15)->get()->pluck('id');
        $trendThisWeek->products()->attach($weekProducts);
        $bestProducts = Product::query()->where('id', '>=', 15)->take(15)->get()->pluck('id');
        $bestSelling = Category::query()->where('name', 'Best Selling')->first();
        $bestSelling->products()->attach($bestProducts);

        $sportProducts = Product::query()->where('id', '>=', 30)->take(15)->get()->pluck('id');
        $sportsCat = Category::query()->where('name', 'Shop By Sport')->first();
        $sportsCat->products()->attach($sportProducts);
    }
    
}