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
        $categoryItems = ["New & Featured",'Men','Women','Kids'];
        foreach ($categoryItems as $categoryItem) {
            $category = Category::query()->create([
                'name' => $categoryItem,
            ]);
            $slug = Str::slug($categoryItem).'.'.$category->id;

        }
    }
}
