<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::resource('products', ProductController::class);
Route::delete('products/{id}' ,[ ProductController::class , 'forceDelete']);
Route::get('products/restore/{id}' ,[ ProductController::class , 'restore']);
