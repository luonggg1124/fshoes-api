<?php

namespace App\Services\OrderDetail;


use App\Services\OrderDetail\OrderDetailServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;


class OrderDetailService implements OrderDetailServiceInterface
{

    public function __construct(protected OrderDetailRepositoryInterface $orderDetailRepository){}

    public function getAll($params): AnonymousResourceCollection
    {

    }

    public function findById(int|string $id)
    {

    }

    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = []){


    }
    public function update(int|string $id ,array $data,array $option = []){

    }

}
