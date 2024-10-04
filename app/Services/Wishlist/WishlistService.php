<?php

namespace App\Services\Wishlist;



use App\Http\Resources\OrdersCollection;
use App\Http\Resources\WishlistResource;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Repositories\Wishlist\WishlistRepositoryInterface;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class WishlistService implements WishlistServiceInterface
{
    public function __construct(protected WishlistRepositoryInterface $wishlistRepository)
    {
    }

    function getAll()
    {
        $allWishList = $this->wishlistRepository->query()->with(['product' , 'user'])->paginate(5);
        return WishlistResource::collection($allWishList);
    }

    function findByUserID(int|string $idUser)
    {
        $allWishList = $this->wishlistRepository->query()->where('user_id' , $idUser)->with(['product'])->paginate(5);
        return WishlistResource::collection($allWishList);
    }

    function create(array $data, array $option = [])
    {
        try{
           return $this->wishlistRepository->create($data);
        }catch (\Exception $e){
            throw new \Exception("Cannot add wishlist");
        }
    }
    function delete(int|string $id)
    {
        try{
            return $this->wishlistRepository->delete($id);
        }catch (\Exception $e){
            throw new \Exception("Cannot delete wishlist");
        }
    }
}
