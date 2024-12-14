<?php

namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use App\Services\User\SocialiteService;
use Illuminate\Http\Request;


class SocialiteController extends Controller
{
   public function __construct(
       protected SocialiteService $socialiteService
   ){}
   public function googleRedirect()
   {
     return $this->socialiteService->redirectToGoogle();
   }

   public function googleCallback()
   {
    try{
      return $this->socialiteService->handleGoogleCallback();
    }catch(\Exception $e){
      return response()->json([
        'error' => __('messages.error-internal-server'),
        'success' => false
      ],500);
    }
   
   }
}
