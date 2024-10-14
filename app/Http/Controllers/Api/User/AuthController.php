<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Services\User\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }


    public function checkEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],400);
        }
        $exists = $this->service->checkEmail($request->email);
        if($exists){
            return \response()->json([
                'exists' => $exists,
                'status' => true,
            ]);
        }else{
            $code = $this->service->getCode($request->email);
            return \response()->json([
                'exists' => $exists,
                'status' => true,
                'code' => $code,
            ]);
        }

    }

    public function register(CreateUserRequest $request){
        try{

            $data = $request->all();
            $user = $this->service->register($data,options: [
                'profile' => $request->profile
            ]);
            $token = auth()->login($user);
            return $this->respondWithToken($token);
        }catch(\Throwable $th){
            Log::error(__CLASS__.'@'.__FUNCTION__,[
                "line" => $th->getLine(),
                "message" => $th->getMessage()
            ]);
            if($th instanceof ValidationException){

                return response()->json([
                    'error' => $th->getMessage()
                ],422);
            }
            return response()->json([
                'error' => $th->getMessage()
            ],500);
        }
    }
//    public function getCode(Request $request)
//    {
//        try {
//            $email = $request->get('email');
//            //$code = $this->service->register();
//            //return $this->respondWithToken($token);
//        } catch (\Throwable $throwable) {
//            if ($throwable instanceof JWTException) {
//                return response()->json([
//                    'status' => false,
//                    'message' => 'Something went wrong',
//                ], 500);
//            }
//            return response()->json([
//                'status' => false,
//                'message' => $throwable->getMessage(),
//            ], 422);
//        }
//    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = $this->service->login($credentials);
            return $this->respondWithToken($token);
        } catch (\Throwable $throwable) {
            if ($throwable instanceof JWTException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                ], 500);
            }
            if ($throwable instanceof AuthenticationException) {
                return response()->json([
                    'status' => false,
                    'message' => $throwable->getMessage(),
                ], 401);
            }
            return response()->json([
                'status' => false,
                'message' => $throwable->getMessage(),
            ], 422);
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
            ], 500);
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

    protected function respondWithToken($token): Response|JsonResponse
    {
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
