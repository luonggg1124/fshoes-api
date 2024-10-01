<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\User\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service){}

    public function getCode(Request $request){
        try {
            $email = $request->get('email');
            $code = $this->service->register()
            return $this->respondWithToken($token);
        }catch (\Throwable $throwable){
            if($throwable instanceof JWTException){
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                ],500);
            }
            return response()->json([
                'status' => false,
                'message' => $throwable->getMessage(),
            ],422);
        }
    }
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = $this->service->login($credentials);
            return $this->respondWithToken($token);
        }catch (\Throwable $throwable){
            if($throwable instanceof JWTException){
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                ],500);
            }
            if($throwable instanceof AuthenticationException){
                return response()->json([
                    'status' => false,
                    'message' => $throwable->getMessage(),
                ],401);
            }
            return response()->json([
                'status' => false,
                'message' => $throwable->getMessage(),
            ],422);
        }

    }
    public function me()
    {
        try {
            $user = $this->service->me();
            return response()->json([
                'status' => true,
                'user' => $user
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ],500);
        }

    }
    public function logout()
    {
        auth()->logout(true);
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token):Response|JsonResponse
    {
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
