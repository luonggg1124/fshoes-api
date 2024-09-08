<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(){
       try{
        $data = Socialite::driver('google')->user();
        return response()->json([
            'user' => $data,
        ]);
       }catch(\Exception $e){
            return response()->json(['error' => 'Something went wrong!']);
       }
        
    }
}
