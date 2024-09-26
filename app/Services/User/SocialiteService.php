<?php

namespace App\Services\User;

use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\User\UserResource;


class SocialiteService extends UserService{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(){
       
        $data = Socialite::driver('google')->user();
        if(!$data) return response()->json(['error' => 'Something went wrong!']);
        $user = $this->userRepository->query()->where('email',$data->email)->first();
        if(!$user){
            $user = $this->create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Str::random(10),
                'avatar' => $data->avatar,
                'google_id' => $data->id,
                'email_verified_at' => now(),
                
            ],[
                'profile' => [
                    'given_name' => $data->user['given_name'],
                    'family_name' => $data->user['family_name'],
                ],
            ]);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        $cookie = cookie('XSRF-TOKEN', $token,24*60,httpOnly:true,sameSite:'strict');
        
        return response()->json([        
            'message' => 'Login successful',
            'success' => true,
            'token' => $token
        ])->cookie($cookie);
    }
}