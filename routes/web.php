<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\SocialiteController;

Route::get('/', function () {
    return response()->json([
        'message' => 'api serve',
        'success' => true
    ]);
});
Route::get('login',function(){
    return 'login page';
});
Route::get('test', function () {
    $path = '/temp/category/XyiMYQQVKWawEHV3t7ZtXYvK1ygGm34QxQQjv2M8.png';
   return view('test',[
       'path' => $path
   ]);
});

Route::get('auth/google/redirect', [SocialiteController::class, 'googleRedirect']);
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
