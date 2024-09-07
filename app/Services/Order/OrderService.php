<?php

namespace App\Services\Order;



use App\Http\Resources\OrdersCollection;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class OrderService implements OrderServiceInterface
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderDetailRepositoryInterface $orderDetailRepository
    ){}

    public function getAll($params): AnonymousResourceCollection
    {
        $orders = $this->orderRepository->query()->with('orderDetails');
        if(isset($params['user_id'])){
            $orders->where('user_id' , $params['user_id']);
        }
        $orders->latest();
        return OrdersCollection::collection(
            $orders->paginate()
        );
    }

    public function findById(int|string $id)
    {
        $cart = $this->orderRepository->query()->where('id', $id)->with(["orderDetails"])->first();
        if(!$cart){
            throw new ModelNotFoundException('Order not found');
        }
        return new OrdersCollection($cart);
    }

    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = []){
        try {
            $order = $this->orderRepository->create($data);
            foreach($data['order_details'] as $detail){
                $detail['order_id'] =  $order->id;
                $this->orderDetailRepository->create($detail);
            }
            return $order;
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(int|string $id ,array $data,array $option = []){
        try {
            $order = $this->orderRepository->update($id,$data);
            return $order;        
         }catch (\Exception $e){
             throw new \Exception('Cannot update order');
         }
    }

}
