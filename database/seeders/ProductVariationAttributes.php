<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use App\Models\ProductAttributeValues;
use App\Models\ProductVariations;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductVariationAttributes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_variations_id = ProductVariations::select('id')->get()->pluck('id')->toArray();
        $attribute_value_id = AttributeValue::select('id')->get()->pluck('id')->toArray();
        for($i =0 ; $i<5 ; $i++){
            ProductAttributeValues::query()->create(
              [
                    'attribute_value_id'=>fake()->randomElement($product_variations_id),
                    'variation_id'=>fake()->randomElement($attribute_value_id)
              ]
            );    
        }
    }
}
