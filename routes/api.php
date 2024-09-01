<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Haven't update , waiting for Frontend 
Route::resource('products', ProductController::class);
Route::get('products/restore/{id}' ,[ ProductController::class , 'restore']);
Route::delete('products/forceDelete/{id}' ,[ ProductController::class , 'forceDelete']);


Route::resource('product-images', ProductImagesController::class);
