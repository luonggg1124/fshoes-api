<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use App\Mail\SendAuthCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AuthService extends UserService
{
    use CanLoadRelationships;
    public function checkEmail(string $email){
        $user = $this->userRepository->findByColumnOrEmail($email);
        if(!$user) return false;
        return true;
    }
    public function register(array $data, array $options = ['profile' => []]){
        $user = DB::transaction(function () use ($data, $options) {
            if ($this->userRepository->query()->where('email', $data['email'])->exists())
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'The email have already been taken'
                ]);
            $data['status'] = 'active';
            $data['nickname'] = $this->createNickname($data['name']);
            $user = $this->userRepository->create($data);

            if (!$user)
                throw new \Exception('Failed to register');
            $this->createProfile($user->id, $options['profile']);
            return $user;
        }, 3);
        return $user;
    }
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
        $code = random_int(1234567, 9876543);
        Mail::to($email)->send(new SendAuthCode($code));
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
