<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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

}
