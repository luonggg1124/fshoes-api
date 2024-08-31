<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=CategorySeeder
        $categoryItems = ['Men','Women','Kids','Kids Shoes','Sale',"New & Featured"];
        foreach ($categoryItems as $categoryItem) {
            $slug = Str::slug($categoryItem).'-'.Str::random(5);
            Category::query()->create([
                'name' => $categoryItem,
                'slug' => $slug,
            ]);
        }
    }
}
