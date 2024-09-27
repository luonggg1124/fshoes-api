<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class AuthService extends UserService
{
    use CanLoadRelationships;
    public function login(array $credentials = [
        'email' => '',
        'password' => '',
    ])
    {
        if(empty($credentials['email'])) throw new \Exception('Email is missing');
        $user = $this->userRepository->findByColumnOrEmail($credentials['email']);
        if(!$user) throw new ModelNotFoundException('User not found');
        if(!Hash::check($credentials['password'], $user->password)) throw new \Exception('Wrong password');
        $token = auth()->login($user);
        return $token;
    }
    public function me()
    {
        $user = auth()->userOrFail();
        return new UserResource(
            $this->loadRelationships($user)
        );
    }
}
