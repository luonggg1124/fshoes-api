<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use App\Notifications\SendAuthenticationCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthService extends UserService
{
    use CanLoadRelationships;
    public function login(array $credentials = [
        'email' => '',
        'password' => '',
    ])
    {
        $user = $this->userRepository->findByColumnOrEmail($credentials['email']);
        if(!$user) throw new ModelNotFoundException('User not found');
        if(!Hash::check($credentials['password'], $user->password)) throw new AuthenticationException('Wrong password');
        $token = auth()->login($user);
        return $token;
    }
    public function getCode(string $email){
        $existingUser = $this->userRepository->findByColumnOrEmail($email);
        if($existingUser) throw ValidationException::withMessages([
            'email' =>'Email already exists'
        ]);
        $code = random_int(1234567, 9876543);
        Mail::to($email)->send(new SendAuthenticationCode($code));
        return $code;
    }
    public function me()
    {
        $user = auth()->userOrFail();
        return new UserResource(
            $this->loadRelationships($user)
        );
    }
}
