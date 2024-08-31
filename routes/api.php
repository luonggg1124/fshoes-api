<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Category Start
Route::get('/categories',[CategoryController::class,'index']);
Route::get('category/{id}',[CategoryController::class,'show']);


// Category End
