<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\App;


class TestController extends Controller
{
    public function test(){
        $summary = [
            'product',
            'product_variation',
            'product_detail',
            'attribute_value',
            'attribute',
            'image',
            'category',
            'user_profile',

        ];
        $product = Product::query()->find(1);
        return response()->json([
            'test' => true,
            'product' => $product->soldProducts
        ],200);

    }
    public function changeLanguage(){
        $arr = ['vi', 'en'];
        $lang = request()->get('lang');
        if(!in_array($lang, $arr)){
            $lang = 'vi';
        }
        App::setLocale($lang);
        return response()->json([
            'test' => true,
            'language' =>  App::getLocale()
        ],200);
    }
}
