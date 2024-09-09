<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;

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

    public function create(Request $request){
        
    }
    public function update(Request $request, string $nickname) {

    }
    public function destroy(string $nickname){

    }
}
