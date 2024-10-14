<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\CreateUserRequest;
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

    public function store(CreateUserRequest $request){


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
