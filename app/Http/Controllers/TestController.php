<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $attributes = [
            'color' => ['red1', 'blue2', 'green3'],
            'size' => ['small4', 'medium5', 'large6'],
            'material' => ['cotton7', 'polyester8'],
        ];
        $result = [[]];
        foreach ($attributes as $attribute => $values){
            $newResult = [];
            foreach ($result as $variation){
                foreach ($values as $value){
                    $newVariation = $variation;
                    $newVariation[$attribute] = $value;
                    $newResult[] = $newVariation;
                }
            }
            $result = $newResult;
        }
        return response()->json([
            'test' => true,
            'variations' => $result
        ],201);

    }

}
