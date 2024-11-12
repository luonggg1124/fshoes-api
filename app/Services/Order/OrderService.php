<?php

namespace App\Services\Order;


use App\Http\Resources\OrdersCollection;
use App\Models\Order;
use App\Models\ProductVariations;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use App\Services\Cart\CartServiceInterface;
use App\Services\OrderHistory\OrderHistoryService;
use App\Services\OrderHistory\OrderHistoryServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\Product\Variation\VariationServiceInterface;
use Cassandra\Exception\InvalidQueryException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class OrderService implements OrderServiceInterface
{

    public function __construct(
        protected OrderRepositoryInterface       $orderRepository,
        protected OrderDetailRepositoryInterface $orderDetailRepository,
        protected OrderHistoryServiceInterface   $orderHistoryService,
        protected ProductRepositoryInterface     $productRepository,
        protected VariationRepositoryInterface   $variationRepository,
        protected CartRepositoryInterface        $cartRepository,
    )
    {
    }

    public function getAll($params): AnonymousResourceCollection
    {
        $orders = $this->orderRepository->query()->with(['orderDetails', 'orderHistory', 'user', 'orderDetails.variation', 'orderDetails.product']);
        if (isset($params['user_id'])) {
            $orders->where('user_id', $params['user_id']);
        }
        $orders->latest();
        return OrdersCollection::collection(
            $orders->paginate()
        );
    }

    public function findById(int|string $id)
    {
        $order = $this->orderRepository->query()->where('id', $id)->with(["orderDetails", 'orderHistory', 'user', 'orderDetails.variation', 'orderDetails.product', 'voucher'])->first();
        if (!$order) {
            throw new ModelNotFoundException('Order not found');
        }
        return new OrdersCollection($order);
    }

    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = [])
    {
        try {
            foreach ($data['order_details'] ?? [] as $detail) {
                if ($detail["product_id"]) {
                    $item = $this->productRepository->query()->where('id', $detail["product_id"])->first();
                } else {
                    $item = $this->variationRepository->query()->where('id', $detail["product_variation_id"])->first();
                }
                if ($item->stock_qty - $detail["quantity"] < 0) {
                    if ($detail["product_id"]) {
                        $message = "Product " . $item->name . " out of stock. There are only have " . $item->stock_qty . " units";
                    } else  $message = "Variation " . $item->name . " out of stock. There are only have " . $item->stock_qty . " units";
                    return response()->json(["message" => $message], 400);
                }
            }

            $order = $this->orderRepository->create($data);
            foreach ($data['order_details'] ?? [] as $detail) {
                $detail['order_id'] = $order->id;
                $this->orderDetailRepository->create($detail);
                if ($detail["product_id"]) {
                    $item = $this->productRepository->query()->where('id', $detail["product_id"])->first();
                } else {
                    $item = $this->variationRepository->query()->where('id', $detail["product_variation_id"])->first();
                }
                $item->stock_qty = $item->stock_qty - $detail["quantity"];
                $item->qty_sold = $item->qty_sold + $detail["quantity"];
                $item->save();
            }
            $this->orderHistoryService->create(["order_id" => $order->id, "user_id" => $data['user_id'], "description" => "Created Order"]);
            $this->cartRepository->query()->where("user_id", $data['user_id'])->delete();
            return response()->json(["message" => "Order created"], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int|string $id, array $data, array $option = [])
    {
        try {
            $order = $this->orderRepository->find($id);
            $orderDetails = $this->orderDetailRepository->query()->where('order_id', $id)->get();

            foreach ($orderDetails as $detail) {
                if ($detail['product_id']) {
                    $item = $this->productRepository->query()->where('id', $detail["product_id"])->first();
                } else $item = $this->variationRepository->query()->where('id', $detail["product_variation_id"])->first();

                if ($data["status"] == 0 || $data["status"] == 7) {
                    $item->stock_qty = $item->stock_qty + $detail["quantity"];
                    $item->qty_sold = $item->qty_sold - $detail["quantity"] > 0 ? $item->qty_sold - $detail["quantity"] : 0;
                }
                $item->save();
            }
            $order = $this->orderRepository->update($id, $data);
            $this->orderHistoryService->create(["order_id" => $id, "user_id" => 1, "description" => "Update Status Order"]);
            return response()->json(["message" => "Update order successful"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Can't update order"], 500);
        } catch (Exception $e) {

        }
    }

    public function me($params): AnonymousResourceCollection
    {
        $orders = $this->orderRepository->query()->with(['orderDetails', 'orderHistory', 'user', 'orderDetails.variation', 'orderDetails.product'])->where('user_id',auth()->user()->id );
        if(isset($params["status"]))$orders->where('status',$params["status"]);
        return OrdersCollection::collection(
            $orders->paginate()
        );
    }

    public function cancelOrder( $id , $data){
        $order = $this->orderRepository->find($id);
        if($order->status>=3 && $data["status"] ==0)return response()->json(["message"=>"Can't cancel order"], 403);
        $order->status = $data["status"];
        $order->save();
        return response()->json(["message"=>"Update order successfully"],200);
    }

}
