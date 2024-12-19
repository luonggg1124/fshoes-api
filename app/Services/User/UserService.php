<?php

namespace App\Services\User;

use App\Http\Resources\ProductResource;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Traits\Paginate;
use App\Http\Traits\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User\UserResource;
use App\Http\Traits\CanLoadRelationships;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\UnauthorizedException;


class UserService implements UserServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;

    protected array $relations = ['profile', 'interestingCategories', 'addresses', 'allAvatars', 'favoriteProducts','group','statistics'];
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
        public UserRepositoryInterface       $userRepository,
        protected ProductRepositoryInterface $productRepository
    )
    {
    }

    public function all()
    {
        $column = request()->query('column') ?? 'id';
        if (!in_array($column, $this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if ($sort !== 'desc' && $sort !== 'asc')
            $sort = 'asc';
        $perPage = request()->query('per_page');
        $paginate = request()->query('paginate');
        if($paginate){
            $users = $this->loadRelationships($this->userRepository->query()->orderBy($column, $sort))->paginate($perPage);
        return [
            'paginator' => $this->paginate($users),
            'data' => UserResource::collection($users->items())
        ];
        }else {
            $users = $this->loadRelationships($this->userRepository->query()->orderBy($column, $sort))->get();
        return [
            
            'data' => UserResource::collection($users)
        ];
        }
        
    }

    public function create(array $data, array $options = ['avatar' => null, 'profile' => []])
    {

        $user = DB::transaction(function () use ($data, $options) {
            if ($this->userRepository->query()->where('email', $data['email'])->exists())
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => __('messages.user.error-email')
                ]);
            if (isset($data) && empty($data['group_id'])) $data['group_id'] = 3;
            
            if(isset($data['is_admin']) && $data['is_admin'] != 'false'){
                $data['is_admin'] = true;
            }
            else {
                $data['is_admin'] = false;
            }
            $data['status'] = 'active';
            $data['nickname'] = $this->createNickname($data['name']);
           
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);
            if (!$user)
                throw new \Exception(__('messages.user.error-could-not-create'));

            if (isset($options['avatar'])) {
                $avatar = $this->createAvatar($user->id, $options['avatar']);
                $user->avatar_url = $avatar['avatar_url'];
                $user->save();
            }
            if(empty($options['profile']) ){
                $options['profile'] = [
                    'given_name' => '',
                    'family_name' => '',
                    'detail_address' => '',
                    'birth_date' => null,
                ];
                
            }
         
           
            $this->createProfile($user->id, $options['profile']);
            return $user;
        }, 3);
        Cache::tags([...$this->relations])->flush();
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

    public function update(string|int $id, array $data, array $options = [
        'avatar' => null, 'profile' => null
    ])
    {
        $user = $this->userRepository->find($id);
        if (!$user) throw new ModelNotFoundException(__('messages.error-not-found'));
        $update = DB::transaction(function () use ($user, $data, $options) {
            if (isset($data['email'])) unset($data['email']);
           
            if(isset($data['is_admin']) && $data['is_admin'] != 'false'){
                $data['is_admin'] = true;
            }
            else {
                $data['is_admin'] = false;
            }
            if(isset($data['password'])){
                $data['password'] = Hash::make($data['password']);
            }else{
                unset($data['password']);
            }
            $user->update($data);

            if (isset($options['avatar'])) {
                $avatar = $this->createAvatar($user->id, $options['avatar']);
                $user->avatar_url = $avatar['avatar_url'];
                $user->save();
            }

            if (isset($data['profile'])) {
                if($user->profile){
                    $user->profile()->update($data['profile']);
                }   else{
                    $this->createProfile($user->id, $options['profile']);
                }
                
            };
            return $user;
        }, 3);
        Cache::tags([...$this->relations])->flush();
        return new UserResource($this->loadRelationships($update));
    }

    public function delete(int|string $id)
    {
        $user = $this->userRepository->find($id);
        $authUser = request()->user();
        if (!$user) throw new ModelNotFoundException(__('messages.error-not-found'));
        if($authUser->id == $user->id) throw new UnauthorizedException(__('messages.forbidden'));
        $user->delete();
        Cache::tags([...$this->relations])->flush();
        return true;
    }

    public function findByNickname(string $nickname)
    {
        $user = $this->userRepository->findByNickname($nickname);
        return new UserResource($this->loadRelationships($user));
    }
    
    public function getFavoriteProduct()
    {
        $user = request()->user();
        if (!$user) throw new AuthorizationException(__('messages.user.error-user'));
        $products = $user->favoriteProducts()->with(['categories'])->get();
        return ProductResource::collection($products);
    }

    public function addFavoriteProduct(int|string $productId)
    {
        $user = request()->user();
        if (!$user) throw new AuthorizationException(__('messages.user.error-user'));
        $product = $this->productRepository->find($productId);
        if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
        $user->favoriteProducts()->syncWithoutDetaching($productId);
        $products = $user->favoriteProducts()->with(['categories'])->get();
        Cache::tags([...$this->relations])->flush();
        return ProductResource::collection($products);
    }

    public function removeFavoriteProduct(int|string $productId)
    {
        $user = request()->user();
        if (!$user) throw new AuthorizationException(__('messages.user.error-user'));
        $product = $this->productRepository->find($productId);
        if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
        $user->favoriteProducts()->detach($productId);
        $products = $user->favoriteProducts()->with(['categories'])->get();
        Cache::tags([...$this->relations])->flush();
        return ProductResource::collection($products);
    }

    public function updateProfile(array $data)
    {

        $user = $this->userRepository->find(request()->user()->id);
        $profile = $user->profile;

        if (!$user) throw new AuthorizationException(__('messages.user.error-user'));
        $updatedUser = DB::transaction(function () use ($user, $data, $profile) {
            if (isset($data['given_name'])) {
                $profile->given_name = $data['given_name'];

            }
            if (isset($data['family_name'])) {
                $profile->family_name = $data['family_name'];
            }
            if (isset($data['detail_address'])) {
                $profile->detail_address = $data['detail_address'];
            }
            if (isset($data['birth_date'])) {
                $profile->birth_date = $data['birth_date'];
            }
            $profile->save();
            $user->name = $profile->given_name . ' ' . $profile->family_name;
            $user->save();

            return $user;
        }, 3);

        Cache::tags([...$this->relations])->flush();
        return new UserResource($this->loadRelationships($updatedUser));
    }

   
    public function userHasOrderCount(){
        $count = $this->userRepository->query()->whereHas('orders')->count();
        return $count;
    }

    public function updateAvatar(UploadedFile $file){
        $userRequest = request()->user();
        $userModel = $this->userRepository->find($userRequest->id);
        if(!$userModel) throw new ModelNotFoundException(__('messages.error-not-found'));
        if($userModel->avatar_public_id)
        $this->deleteImageCloudinary($userModel->avatar_public_id);
        $avatar = $this->uploadImageCloudinary( $file,'avatars');
        
        $userModel->avatar_url = $avatar['path'];
        $userModel->avatar_public_id = $avatar['public_id'];
        $userModel->save();
        Cache::tags([...$this->relations])->flush();
        return new UserResource($this->loadRelationships($userModel));
    }
}
