<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //php artisan db:seed --class=DiscountSeeder
        $discounts = Sale::factory(3)->create();
        $count = 15;
        foreach($discounts as $discount){
            $products = Product::all();
            foreach ($products as $product){
                if($product->variations){
                    $product->sales()->syncWithPivotValues([$discount->id],[
                        'quantity' => 40
                    ],false);
                    $product->variations()->get()->pluck('id');
                    $discount->variations()->syncWithPivotValues($product->variations()->get()->pluck('id'),[
                        'quantity' => 10
                    ],false);
                }else{
                    $discount->products()->syncWithPivotValues($product->id,[
                        'quantity' => 8
                    ],false);
                }
            }

        }
    }
}
