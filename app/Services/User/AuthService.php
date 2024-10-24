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
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthService extends UserService
{
    use CanLoadRelationships;
    public function checkEmail(string $email){
        $user = $this->userRepository->findByColumnOrEmail($email);
        if(!$user) return false;
        return true;
    }
    public function register(array $data, array $options = ['profile' => []]){
        $credential = [
            'email' => $data['email'],
            'password' => $data['password']
        ];
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
        if($user)
        return $this->login($credential);
        else throw new Exception('something went wrong');
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
        $refresh_token = auth()->claims([
            'exp' => now()->addDays(30)->timestamp,
        ])->attempt($credentials);
        $user->load('profile');
        return [
            'access_token' => $token,
            'refresh_token' => $refresh_token,
            'user' => new UserResource($user)
        ];
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
