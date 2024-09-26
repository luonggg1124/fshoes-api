<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Support\Str;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=VariationSeeder
        $attributes = ['colour','size','nation'];
        foreach($attributes as $a){
           Attribute::create([
            'name' => $a
           ]);
        }

        $colourValue = ['red','yellow','white','black'];
        foreach($colourValue as $c){
            AttributeValue::create([
                'attribute_id' => 1,
                'value' => $c
            ]);
        }
        for($i = 35; $i <= 42; $i+=0.5 ) AttributeValue::create([
            'attribute_id' => 2,
            'value' => $i
        ]);
        $nationValues = ['vietnam','china','singapore','thailand'];
        foreach($nationValues as $n)AttributeValue::create([
            'attribute_id' => 3,
            'value' => $n
        ]);

        foreach(Product::all() as $p){
            $attrId = AttributeValue::where('attribute_id',1)->get();
            foreach($attrId as $a){
                $variation = $p->variations()->create([
                    'sku' => $p->sku.'-'.Str::random(5),
                    'price' => $p->price+(1/10 * $p->price),
                    'sale_price' => $p->sale_price+(1/10 * $p->sale_price),
                    'is_sale' => false,
                    'stock_qty' => random_int(20,70),
                    'qty_sold' => random_int(20,70),
                ]);
                DB::table('product_variation_attributes')->insert([
                    'variation_id' => $variation->id,
                    'attribute_value_id' => $a->id
                ]);
                foreach(AttributeValue::where('attribute_id',2)->get() as $s){
                    
                    DB::table('product_variation_attributes')->insert([
                        'variation_id' => $variation->id,
                        'attribute_value_id' => $s->id
                    ]);
                    DB::table('product_variation_attributes')->insert([
                        'variation_id' => $variation->id,
                        'attribute_value_id' => random_int(1,4)
                    ]);
                }
            }
            
        }
        
    }

}
