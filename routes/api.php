<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Category Start

Route::apiResource('category', CategoryController::class)
    ->parameter('category', 'id');


// Category End

//Product Start
Route::apiResource('product',ProductController::class)->parameter('product','id');

//Product End


// Attribute - Attribute Value Start
Route::apiResource('attribute.value',ProductController::class)->parameters(['attribute'=>'aid','value' => 'vid']);
//Attribute - Attribute Value End