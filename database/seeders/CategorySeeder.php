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
        $mains = ["Mới & Đặc Sắc",'Đàn Ông','Phụ Nữ','Trẻ Con'];
        foreach ($mains as $name) {
            $category = Category::query()->create([
                'name' => $name,
                'is_main' => 1
            ]);
            $slug = Str::slug($name).'.'.$category->id;
            $category->slug = $slug;
            $category->save();
        }
        $homePage = ['Xu Hướng Tuần Này','Bán Chạy','Thể Thao'];
        $count = 1;
        foreach ($homePage as $c) {
            
            $category = Category::query()->create([
                'name' => $c,
                'is_main' => 2,
                'display' => $count++
            ]);
            $slug = Str::slug($c).'.'.$category->id;
            $category->slug = $slug;
            $category->save();
        }
        $children = ['Hàng Mới Về','Giày Mới Nhất', 'Con Trai','Con Gái','Giày Thể Thao Mới','Nike','Adidas'];
        foreach ($children as $cat) {
            $category = Category::query()->create([
                'name' => $cat,
                'is_main' => 0
            ]);
            $arrs = [
                random_int(1,2),
                random_int(3,4),
            ];
            $category->parents()->attach($arrs);
            $slug = Str::slug($cat).'.'.$category->id;
            $category->slug = $slug;
            $category->save();
        }
    }
}
