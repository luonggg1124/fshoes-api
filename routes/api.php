<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\User\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('auth/redirect-to/login',function(){
    $redirect_login = env('APP_URL').':8000/login';
    return response()->redirectTo($redirect_login);
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
  
});

Route::apiResource('user',UserController::class)->parameter('user','nickname')->except(['index','destroy']);
// End Auth

// Category Start

Route::apiResource('category', CategoryController::class)
    ->parameter('category', 'id')->only(['index','show']);


// Category End

//Product Start
Route::apiResource('product',ProductController::class)->parameter('product','slug');

//Product End


// Attribute - Attribute Value Start
Route::apiResource('attribute.value',ProductController::class)->parameters(['attribute'=>'aid','value' => 'vid']);
//Attribute - Attribute Value End