<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;

class UserService implements UserServiceInterface
{
    use CanLoadRelationships,Cloudinary,Paginate;
    protected array $relations = ['profile','interestingCategories','addresses','allAvatars'];
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
    public function create(array $data,array $options = ['avatar' => null,'profile' => null]){
        if($this->userRepository->query()->where('email',$data['email'])->exists()) throw new \Exception('The user already exists');
        $data['status'] = 'active';
        $user = $this->userRepository->create($data);

        if(!$user) throw new \Exception('Could not create user');

        if($options['avatar'] && $options['avatar'] instanceof UploadedFile){
            $this->createAvatar($user->id,$options['avatar'] );
        }
        
       $this->createProfile($user->id,$options['profile']);
    
        return new UserResource($this->loadRelationships($user));
    }
    public function update(string $nickname, array $data, array $options = []){

    }
    public function findByNickname(string $nickname){
        $user = $this->userRepository->findByNickname($nickname);
        if(!$user) throw new \Exception('User not found');
        return new UserResource($this->loadRelationships($user));
    }
    protected function createAvatar(int|string $userId,UploadedFile $file)
    {
        $upload = $this->uploadImageCloudinary($file,'avatars');
        $dataAvatar = [
            'user_id' => $userId,
            'avatar_url' => $upload['path'],
            'cloudinary_public_id' => $upload['public_id'],
            'is_active' => true
        ];
        return $this->userRepository->createAvatar($dataAvatar);
    }
    public function createProfile(string|int $userId, array $data)
    {
        $data['user_id'] = $userId;
        return $this->userRepository->createProfile($data);
    }
}
