<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderDetailsController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\PaymentOnline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Category Start

Route::apiResource('category', CategoryController::class)
    ->parameter('category', 'id');


// Category End


//Cart
Route::apiResource('cart' , CartController::class);

//Order
Route::apiResource('orders' , OrdersController::class);


//Order Detail
Route::apiResource('order-details' , OrderDetailsController::class);

//Payment
Route::post('momo' , [PaymentOnline::class , 'momo']);
Route::post('vnpay' , [PaymentOnline::class , 'vnpay']);
Route::post('stripe' , [PaymentOnline::class , 'stripe']);
Route::post('paypal' , [PaymentOnline::class , 'paypal']);
        Route::get('success-paypal' , [PaymentOnline::class , 'successPaypal'])->name('successPaypal');
        Route::get('error-paypal' , [PaymentOnline::class , 'errorPaypal'])->name('errorPaypal');
