<?php

namespace App\Services\User;

use Illuminate\Support\Str;
use App\Http\Traits\Paginate;
use App\Http\Traits\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Validation\ValidationException;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;
    protected array $relations = ['profile', 'interestingCategories', 'addresses', 'allAvatars','favoriteProducts'];
    private array $columns = [
        'nickname',
        'name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'is_admin',
        'is_active',
        'status',
        'created_at',
        'updated_at',
    ];
    public function __construct(
        public UserRepositoryInterface $userRepository
    ) {
    }

    public function all()
    {
        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if ($sort !== 'desc' && $sort !== 'asc')
            $sort = 'asc';
        $perPage = request()->query('per_page');
        $users = $this->loadRelationships($this->userRepository->query()->orderBy($column, $sort))->paginate($perPage);
        return [
            'paginator' => $this->paginate($users),
            'data' => UserResource::collection($users->items())
        ];
    }
    public function create(array $data, array $options = ['avatar' => null, 'profile' => []])
    {

            $user = DB::transaction(function () use ($data, $options) {
            if ($this->userRepository->query()->where('email', $data['email'])->exists())
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'The email have already been taken'
                ]);
            if(isset($data) && empty($data['group_id'])) $data['group_id'] = 1;

            $data['status'] = 'active';
            $data['nickname'] = $this->createNickname($data['name']);
            $user = $this->userRepository->create($data);

            if (!$user)
                throw new \Exception('Could not create user');

            if (isset($options['avatar'])) {
                $this->createAvatar($user->id, $options['avatar']);
            }

            $this->createProfile($user->id, $options['profile']);
            return $user;
        }, 3);
        return new UserResource($this->loadRelationships($user));
    }

    protected function createAvatar(int|string $userId, UploadedFile|string $avatar = null)
    {
        if ($avatar instanceof UploadedFile) {
            $upload = $this->uploadImageCloudinary($avatar, 'avatars');
            $dataAvatar = [
                'user_id' => $userId,
                'avatar_url' => $upload['path'],
                'cloudinary_public_id' => $upload['public_id'],
                'is_active' => true
            ];
        } else {
            $dataAvatar = [
                'user_id' => $userId,
                'avatar_url' => $avatar,
                'cloudinary_public_id' => null,
                'is_active' => true
            ];
        }
        return $this->userRepository->createAvatar($dataAvatar);
    }
    protected function createProfile(string|int $userId, array $data = [])
    {
        $data['user_id'] = $userId;
        return $this->userRepository->createProfile($data);
    }

    protected function createNickname(string|array $name)
    {
        $nickname = '';
        if (is_array($name)) {
            foreach ($name as $n)
                $nickname .= Str::slug($n);
            $nickname .= '.' . Str::random(5);
            if ($this->userRepository->query()->where('nickname', $nickname)->exists()) {
                return $this->createNickname($name);
            }
            return $nickname;
        }
        $arrName = explode(' ', $name);
        foreach ($arrName as $n)
            $nickname .= Str::slug($n);
        $nickname .= '.' . Str::random(5);
        if ($this->userRepository->query()->where('nickname', $nickname)->exists()) {
            return $this->createNickname($name);
        }
        return $nickname;
    }

    public function update(string $nickname, array $data, array $options = [])
    {
    }
    public function findByNickname(string $nickname)
    {
        $user = $this->userRepository->findByNickname($nickname);
        return new UserResource($this->loadRelationships($user));
    }

}
