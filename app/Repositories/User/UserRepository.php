<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\User\UserAddress;
use App\Models\User\UserAvatar;
use App\Models\User\UserProfile;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(
        User $model,
        public UserProfile $profile,
        public UserAddress $address,
        public UserAvatar $avatar
    )
    {
        parent::__construct($model);
    }

    public function createProfile(array $data){
        return $this->profile->query()->create($data);
    }
    public function updateProfile(int|string $userId, array $data){
        $profile = $this->profile->query()->where('user_id',$userId)->first();
        if(!$profile) throw new ModelNotFoundException('Could not find the user profile');
        $profile->update($data);
        return $profile;
    }

    public function findByNickname(string $nickname){
        $user = $this->model->where('nickname',$nickname)->first();
        if(!$user) throw new ModelNotFoundException('Could not find the user');
        return $user;
    }
    public function findByColumnOrEmail(string $data,string $column = ''){
        $columns = [
            'nickname',
            'email',
            'google_id'
        ];
        if(!in_array($column,$columns)){
          $column = 'email';
        }
        $user = $this->model->where($column,$data)->first();
        if(!$user) throw new ModelNotFoundException("Could not find the user whose $column is $data");
        return $user;
    }
    public function createAvatar(array $data){
        return $this->avatar->query()->create($data);
    }
}
