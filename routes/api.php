<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderDetailsController;
use App\Http\Controllers\Api\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Category Start

Route::apiResource('category', CategoryController::class)
    ->parameter('category', 'id');


// Category End

Route::apiResource('cart' , CartController::class);

Route::apiResource('orders' , OrdersController::class);
Route::apiResource('order-details' , OrderDetailsController::class);
