<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    )
    {

    }
    public function login(Request $request){

    }
    public function register(Request $request){

    }
    public function logout(Request $request){

    }
}
