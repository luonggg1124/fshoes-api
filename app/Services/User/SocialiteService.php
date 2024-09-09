<?php

namespace App\Services\User;

use Laravel\Socialite\Facades\Socialite;

class SocialiteService extends UserService{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(){
       try{
        $data = Socialite::driver('google')->user();
        //$this->create([]);
        return response()->json([
            'user' => $data,
        ]);
       }catch(\Exception $e){
            return response()->json(['error' => 'Something went wrong!']);
       }
        
    }
}