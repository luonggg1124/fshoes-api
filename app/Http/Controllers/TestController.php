<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $product = Product::query()->find(1);
        return response()->json([
            'test' => true,
            'product' => $product->soldProducts
        ],200);

    }

}
