<?php


use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderDetailsController;
use App\Http\Controllers\Api\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Review\ReviewController;
use App\Http\Controllers\Api\User\SocialiteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('auth/redirect-to/login',function(){
    return redirect()->to('/login');
})->name('login');


//Admin
Route::group(['middleware' => ['auth:sanctum','is_admin']], function(){
    Route::apiResource('category',CategoryController::class)->parameter('category','id')->except(['index','show']);
    Route::delete('user/{nickname}',[UserController::class,'destroy']);

});

// End Admin

Route::get('user',[UserController::class,'index']);
// Auth
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::apiResource('user',UserController::class)->parameter('user','nickname')->except(['index','destroy']);
    Route::post('logout',[UserController::class,'logout']);
});


Route::post('login',[UserController::class,'login']);
// End Auth

// Category Start

Route::apiResource('category', CategoryController::class)
    ->parameter('category', 'id')->only(['index','show']);


// Category End


Route::apiResource('cart' , CartController::class);

Route::apiResource('orders' , OrdersController::class);
Route::apiResource('order-details' , OrderDetailsController::class);

//Product Start
Route::apiResource('product',ProductController::class)->parameter('product','id');

//Product End

// Review
Route::apiResource('review',ReviewController::class)->parameter('review','id');

// End Review


// Attribute - Attribute Value Start
Route::apiResource('attribute',\App\Http\Controllers\Api\Attribute\AttributeController::class)->parameter('attribute','id');
Route::apiResource('attribute.value',\App\Http\Controllers\Api\Attribute\Value\AttributeValueController::class)->parameters(['attribute'=>'aid','value' => 'id']);
//Attribute - Attribute Value End
//Route::get('api/auth/google/redirect', [SocialiteController::class, 'googleRedirect']);
//Route::post('auth/google/callback', [SocialiteController::class, 'googleCallback']);

Route::get('test',[UserController::class,'test']);

