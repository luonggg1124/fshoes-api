<?php

namespace App\Services\Wishlist;



use App\Http\Resources\OrdersCollection;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class WishlistService implements WishlistServiceInterface
{

    function getAll($params)
    {

    }

    function findByUserID(int|string $idUser)
    {
        // TODO: Implement findByUserID() method.
    }

    function create(array $data, array $option = [])
    {
        // TODO: Implement create() method.
    }

    function delete(int|string $id)
    {
        // TODO: Implement delete() method.
    }
}
