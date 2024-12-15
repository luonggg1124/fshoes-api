<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use BaconQrCode\Common\Mode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\User\UserServiceInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;

class UserController extends Controller
{
    public function __construct(
        public UserServiceInterface $userService
    ) {}


    public function index()
    {
        return response()->json([
            'users' => $this->userService->all()
        ]);
    }
    public function getFavoriteProduct()
    {
        try {
            $products = $this->userService->getFavoriteProduct();
            return response()->json([
                'status' => true,
                'products' => $products
            ]);
        } catch (\Throwable $throw) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $throw->getLine(),
                "message" => $throw->getMessage()
            ]);
            if ($throw instanceof AuthorizationException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 401);
            if ($throw instanceof ModelNotFoundException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 404);
            return response()->json([
                'status' => false,
                'message' => __('messages.error-internal-server')
            ], 500);
        }
    }
    public function addFavoriteProduct(int|string $product_id)
    {
        try {
            $products = $this->userService->addFavoriteProduct($product_id);
            return response()->json([
                'status' => true,
                'message' => __('messages.user.error-add-favorite'),
                'products' => $products
            ], 201);
        } catch (\Throwable $throw) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $throw->getLine(),
                "message" => $throw->getMessage()
            ]);
            if ($throw instanceof AuthorizationException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 401);
            if ($throw instanceof ModelNotFoundException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 404);
            return response()->json([
                'status' => false,
                'message' => __('messages.error-internal-server')
            ], 500);
        }
    }
    public function removeFavoriteProduct(int|string $product_id)
    {
        try {
            $products = $this->userService->removeFavoriteProduct($product_id);
            return response()->json([
                'status' => true,
                'message' => __('messages.user.error-add-favorite'),
                'products' => $products
            ], 200);
        } catch (\Throwable $throw) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $throw->getLine(),
                "message" => $throw->getMessage()
            ]);
            if ($throw instanceof AuthorizationException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 401);
            if ($throw instanceof ModelNotFoundException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 404);
            return response()->json([
                'status' => false,
                'message' => __('messages.error-internal-server')
            ], 500);
        }
    }
    public function updateProfile(Request $request)
    {
        try {
            $data = $request->all();

            $user = $this->userService->updateProfile($data);
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => __('messages.user.error-profile'),
            ], 201);
        } catch (\Throwable $throw) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $throw->getLine(),
                "message" => $throw->getMessage()
            ]);
            if ($throw instanceof AuthorizationException) return response()->json([
                'status' => false,
                'message' => $throw->getMessage()
            ], 401);
            return response()->json([
                'status' => false,
                'message' => __('messages.error-internal-server')
            ], 500);
        }
    }
    public function show(string $nickname)
    {
        try {
            $user = $this->userService->findByNickname($nickname);
            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $data = $request->all();
            $user = $this->userService->create($data);
            return response()->json([
                'status' => true,
                'message' => __('messages.created-success'),
                'user' => $user
            ], 201);
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $th->getLine(),
                "message" => $th->getMessage()
            ]);
            if ($th instanceof ValidationException) {

                return response()->json([
                    'error' => $th->getMessage()
                ], 422);
            }
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function update(UpdateUserRequest $request, string|int $id)
    {
        try {
            $data = $request->all();
            $user = $this->userService->update($id, $data, options: [
                'avatar' => $request->avatar,
                'profile' => $request->profile
            ]);
            return response()->json([
                'status' => true,
                'message' => __('messages.update-success'),
                'user' => $user
            ], 201);
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $th->getLine(),
                "message" => $th->getMessage()
            ]);
            if ($th instanceof ValidationException) {

                return response()->json([
                    'error' => $th->getMessage()
                ], 422);
            }
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function destroy(string|int $id)
    {
        try {
            $this->userService->delete($id);
            return response()->json([
                'status' => true,
                'message' => __('messages.delete-success'),
            ]);
        } catch (\Throwable $throw) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                "line" => $throw->getLine(),
                "message" => $throw->getMessage()
            ]);
            if ($throw instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => $throw->getMessage()
                ], 404);
            }
            return response()->json([
                'status' => false,
                'message' => __('messages.error-internal-server')
            ], 500);
        }
    }


    public function userHasOrderCount()
    {
        return response()->json([
            'count' => $this->userService->userHasOrderCount()
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $file = $request->avatar;
        if ($file instanceof UploadedFile) {
            $user = $this->userService->updateAvatar($file);
            return response()->json([
                'status' => true,
                'message' => __('messages.update-success'),
                'user' => $user
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('messages.error-upload'),
            ], 422);
        }
        // try {
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Something went wrong'
        //     ], 500);
        // }
    }

    public function test()
    {
        // return [$this->userService->createNickname('Louis Nguyen'),$this->userService->createNickname(['Lương Nguyễn', 'Minh'])];
    }
}
