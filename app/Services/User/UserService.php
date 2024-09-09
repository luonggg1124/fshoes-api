<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\User\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    use CanLoadRelationships,Cloudinary,Paginate;
    protected array $relations = ['profile','interestingCategories','addresses'];
    public function __construct(
        public UserRepositoryInterface $userRepository
    ){

    }
    public function all(){
        $column = request()->query('column') ?? 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $perPage = request()->query('per_page');
        $users = $this->loadRelationships($this->userRepository->query()->orderBy($column, $sort))->paginate($perPage);
        return [
            'paginator' => $this->paginate($users),
            'data' => UserResource::collection($users->items())
        ];
    }
    public function create(array $data,array $options = []){
        $user = $this->userRepository->create($data);
        if(!$user) throw new \Exception('Could not create user');

        if($options['profile']){
            $options['profile']['user_id'] = $user->id;
            $this->userRepository->createProfile($options['profile']);
        }
        return new UserResource($this->loadRelationships($user));
    }
    public function update(string $nickname, array $data, array $options = []){

    }
    public function findByNickname(string $nickname){
        $user = $this->userRepository->findByNickname($nickname);
        if(!$user) throw new \Exception('User not found');
        return new UserResource($this->loadRelationships($user));
    }
    
}
