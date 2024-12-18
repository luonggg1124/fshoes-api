<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

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
        
        Cache::put('language',$lang,30*24*60*60*1000);
        App::setLocale($lang);
        return response()->json([
            'status' => true,
            'language' =>  App::getLocale()
        ],200);
    }
    public function testHtml(){
        return view('mail.paid-order');
    }
}
