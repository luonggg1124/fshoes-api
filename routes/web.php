<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;

Route::get('/', function () {
    return response()->json([
        'message' => 'api serve',
        'success' => true
    ]);
});

Route::get('test', function () {
    $path = '/temp/category/XyiMYQQVKWawEHV3t7ZtXYvK1ygGm34QxQQjv2M8.png';
   return view('test',[
       'path' => $path
   ]);
});

Route::get('auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
