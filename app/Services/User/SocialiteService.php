<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use Laravel\Socialite\Facades\Socialite;

class SocialiteService extends UserService{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(){
       
        $data = Socialite::driver('google')->user();
        if(!$data) return response()->json(['error' => 'Something went wrong!']);
        
        $user = $this->create([
            'name' => $data->name,
            'email' => $data->email,
            'avatar' => $data->avatar,
            'google_id' => $data->id,
            'email_verified_at' => now(),
            
        ],[
            'profile' => [
                'given_name' => $data->user['given_name'],
                'family_name' => $data->user['family_name'],
            ],
        ]);
        return response()->json([        
            'user' => new UserResource($user->load('profile'))
        ]);
      
       
        
    }
}