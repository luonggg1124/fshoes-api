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
            $products = Product::query()->where('id','<=',$count)->get();
            foreach ($products as $product){
                if($product->variations){
                    $product->variations()->get()->pluck('id');
                    $discount->variations()->attach($product->variations()->get()->pluck('id'),[
                        'quantity' => 10
                    ]);
                }else{
                    $discount->products()->attach($product->id,[
                        'quantity' => 7
                    ]);
                }
            }
            $count+=15;
        }
    }
}
