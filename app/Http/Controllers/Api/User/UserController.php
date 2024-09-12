<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\User\UserServiceInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct(
        public UserServiceInterface $userService
    ) {}
    
    public function login(Request $request){
        try{
            $data =$request->only('email','password');
            
            $token = $this->userService->login($data['email'], $data['password']);
            $cookie = cookie('XSRF-TOKEN', $token,24*60,httpOnly:true,sameSite:'strict');
            return response()->json([
                'message' => 'Login successful',
                'success' => true,
                'token' => $token
            ])->cookie($cookie);
        }catch(\Throwable $th){
            Log::error(__CLASS__.'@'.__FUNCTION__,[
                "line" => $th->getLine(),
                "message" => $th->getMessage()
            ]);
            if($th instanceof ValidationException){
                return response()->json([
                    'error' => $th->getMessage()
                ],422);
            }elseif($th instanceof ModelNotFoundException){
                return response()->json([
                    'error' => 'Email not found'
                ],404);
            }
            return response()->json([
                'error' => $th->getMessage()
            ],500);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successful',
           'success' => true
        ])->withCookie(cookie()->forget('XSRF-TOKEN'));
    }
    public function index(){
        return response()->json([
            'users' => $this->userService->all()
        ]);
    }
    public function show(string $nickname) {
        try{
            $user = $this->userService->findByNickname($nickname);
            return response()->json([
                'user' => $user
            ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request){
        
           
        try{
            $data = $request->all();
            $user = $this->userService->create($data);
            return response()->json([
                'user' => $user
            ],201);
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
    public function update(Request $request, string $nickname) {

    }
    public function destroy(string $nickname){

    }

    public function test(){
        // return [$this->userService->createNickname('Louis Nguyen'),$this->userService->createNickname(['Lương Nguyễn', 'Minh'])];
    }
}
