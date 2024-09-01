<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for($i =0 ; $i<2 ; $i++){
            Attribute::query()->create(
              [
                'name'=>fake()->name()
              ]
            );    
        }
    }
}
