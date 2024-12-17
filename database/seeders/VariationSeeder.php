<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $attributes = ['Màu', 'Kích Thước'];
        foreach ($attributes as $a) {
            $attribute = Attribute::create([
                'name' => $a,
                'is_filter' => true
            ]);
           
        }

        $colourValue = ['Đỏ', 'Vàng', 'Trắng', 'Đen','Xanh Lá'];
        foreach ($colourValue as $c) {
            AttributeValue::create([
                'attribute_id' => 1,
                'value' => $c
            ]);
        }
        for ($i = 37; $i <= 42; $i++) AttributeValue::create([
            'attribute_id' => 2,
            'value' => $i
        ]);
        

        // foreach (Product::query()->where('id','>',15)->take(15)->get() as $p) {
        //     $attributes = [
        //         'color' => Attribute::query()->find(1)->values()->take(2)->pluck('id'),
        //         'size' => Attribute::query()->find(2)->values()->take(2)->pluck('id'),
        //     ];
        //     $result = [[]];
        //     foreach ($attributes as $attribute => $values) {
        //         $new = [];
        //         foreach ($result as $variation){
        //             foreach ($values as $value){
        //                 $newVariation = $variation;
        //                 $newVariation[$attribute] = $value;
        //                 $new[] = $newVariation;
        //             }
        //         }
        //         $result = $new;
        //     }
        //     foreach ($result as $var){
        //         $variation = $p->variations()->create([
        //             'sku' => $p->sku . '-' . Str::random(5),
        //             'price' => $p->price + (random_int(1,5) / 10 * $p->price),
        //             'import_price' => $p->price - (random_int(1,5) / 10 * $p->price),
        //             'stock_qty' => random_int(20, 70),
        //             'qty_sold' => random_int(20, 70),
        //         ]);


        //         $variation->values()->attach($var);
        //         $values = $variation->values()->pluck('value');
        //         $valueArr = [];
        //         foreach ($values as $value) {
        //             $v = Str::slug($value);
        //             $valueArr[] = $v;
        //         }
        //         $valueStr = implode('-', $valueArr);
        //         $slug = $valueStr . '.' . $variation->id;
        //         $variation->name = $variation->product->name.' '.'['.implode(' - ',[...$values]).']';
        //         $variation->classify = implode(' - ',[...$values]);
        //         $variation->slug = $slug;
        //         $variation->save();
        //         $images = Image::factory(3)->create();
        //         foreach ($images as $image) {
        //             DB::table('product_variation_image')->insert([
        //                 'product_variation_id' => $variation->id,
        //                 'image_id' => $image->id,
        //             ]);
        //         }
        //     }
        // }
        // foreach (Product::query()->where('id','<','16')->take(15)->get() as $p) {
        //     $attributes = [
        //         'color' => Attribute::query()->find(1)->values()->where('id','>',2)->take(2)->pluck('id'),
        //         'size' => Attribute::query()->find(2)->values()->where('id','>',6)->take(2)->pluck('id'),
        //     ];
        //     $result = [[]];
        //     foreach ($attributes as $attribute => $values) {
        //         $new = [];
        //         foreach ($result as $variation){
        //             foreach ($values as $value){
        //                 $newVariation = $variation;
        //                 $newVariation[$attribute] = $value;
        //                 $new[] = $newVariation;
        //             }
        //         }
        //         $result = $new;
        //     }
        //     foreach ($result as $var){
        //         $variation = $p->variations()->create([
        //             'sku' => $p->sku . '-' . Str::random(5),
        //             'price' => $p->price + (random_int(1,5) / 10 * $p->price),
        //             'import_price' => $p->price - (random_int(1,5) / 10 * $p->price),
        //             'stock_qty' => random_int(20, 70),
        //             'qty_sold' => random_int(20, 70),
        //         ]);
        //         $variation->values()->attach($var);
        //         $values = $variation->values()->pluck('value');
        //         $valueArr = [];
        //         foreach ($values as $value) {
        //             $v = Str::slug($value);
        //             $valueArr[] = $v;
        //         }
        //         $valueStr = implode('-', $valueArr);
        //         $slug = $valueStr . '.' . $variation->id;
        //         $variation->name = $variation->product->name.' '.'['.implode(' - ',[...$values]).']';
        //         $variation->classify = implode(' - ',[...$values]);
        //         $variation->slug = $slug;
        //         $variation->save();
        //         $images = Image::factory(3)->create();
        //         foreach ($images as $image) {
        //             DB::table('product_variation_image')->insert([
        //                 'product_variation_id' => $variation->id,
        //                 'image_id' => $image->id,
        //             ]);
        //         }
        //     }
        // }
    }

}
