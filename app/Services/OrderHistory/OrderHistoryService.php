<?php

namespace App\Services\OrderHistory;


use App\Http\Resources\OrdersCollection;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Repositories\OrderHistory\OrderHistoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class OrderHistoryService implements OrderHistoryServiceInterface
{

    public function __construct(
        protected OrderHistoryRepositoryInterface $orderHistoryRepository,
    )
    {
    }

    public function getAll($params)
    {

    }

    public function findById(int|string $id)
    {

    }


    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = [])
    {
        try {
            return $this->orderHistoryRepository->create($data);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function update(int|string $id, array $data, array $option = [])
    {

    }



}
