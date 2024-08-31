<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributeValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = Attribute::select('id')->get()->pluck('id')->toArray();
        for($i =0 ; $i<5 ; $i++){
            AttributeValue::query()->create(
              [
                'attribute_id' => fake()->randomElement($attributes), 
                'value'=>fake()->name()
              ]
            );    
        }
    }
}
