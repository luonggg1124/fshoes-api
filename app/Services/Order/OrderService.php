<?php

namespace App\Services\Order;


use App\Http\Traits\Paginate;
use Exception;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Voucher;
use App\Mail\CreateOrder;
use App\Models\ProductVariations;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrdersCollection;
use App\Services\Cart\CartServiceInterface;
use Cassandra\Exception\InvalidQueryException;
use Illuminate\Validation\UnauthorizedException;
use App\Services\Product\ProductServiceInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\OrderHistory\OrderHistoryService;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\OrderHistory\OrderHistoryServiceInterface;
use App\Services\Product\Variation\VariationServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use App\Repositories\Product\Variation\VariationRepositoryInterface;


class OrderService implements OrderServiceInterface
{
    use Paginate;
    public function __construct(
        protected OrderRepositoryInterface       $orderRepository,
        protected OrderDetailRepositoryInterface $orderDetailRepository,
        protected OrderHistoryServiceInterface   $orderHistoryService,
        protected ProductRepositoryInterface     $productRepository,
        protected VariationRepositoryInterface   $variationRepository,
        protected CartRepositoryInterface        $cartRepository,
        protected UserRepositoryInterface        $userRepository,
    ) {}

    public function getAll($params): AnonymousResourceCollection
    {
        $orders = $this->orderRepository->query()->with(['orderDetails', 'orderHistory', 'user', 'orderDetails.variation', 'orderDetails.product'])->orderBy('created_at', 'desc');
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

            if (isset($data["voucher_id"])) {
                $voucher = Voucher::find($data["voucher_id"]);
                $voucher->quantity--;
                $voucher->users()->attach(request()->user()->id);
                if ($voucher->quantity < 0) return response()->json(["message" => "Voucher is out of uses"], 500);
                $voucher->save();
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
                
                $item->sales()->updateExistingPivot($item->currentSale()->id,[
                    'quantity' => $detail["quantity"] > $item->currentSale()->pivot->quantity ? 0 : $item->currentSale()->pivot->quantity - $detail["quantity"],
                ]);
                
                $item->save();
            }
            if (request()->user()) {
                $this->orderHistoryService->create(["order_id" => $order->id, "user_id" => null, "description" => request()->user()->name . " created order"]);
            } else $this->orderHistoryService->create(["order_id" => $order->id, "user_id" => null, "description" => "Guess" . " created order"]);

            if(isset($data['cart_ids'])){
                foreach($data['cart_ids'] as $id){
                        $this->cartRepository->delete($id);
                }
            }
            Mail::to($order->receiver_email)->send(new CreateOrder($order->id));
            dispatch(new \App\Jobs\CreateOrder($order->id, $order->receiver_email))->delay(now()->addSeconds(5));
            return response()->json([
                "message" => "Order created",
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int|string $id, array $data, array $option = [])
    {
        if (isset($data["status"]) && $data["status"] == "0" && !isset($data["reason_cancelled"])) {
            return response()->json(["message" => "Please provide specific reason."], 403);
        }
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
            $message = "";
            switch ($data["status"]) {
                case 0:
                    $message = (request()->user()->name ? "User " . request()->user()->name : "Guess") . " cancelled order";
                    break;
                case 2:
                    $message = "Admin confirmed order";
                    break;
                case 3:
                    $message = "Order is being delivered";
                    break;
                case 4:
                    $message = "Order was delivered";
                    break;
                case 5:
                    $message = "Return order processing";
                    break;
                case 6:
                    $message = "Order returned processing";
                    break;
                case 7:
                    $message = "Order is returned";
                    break;
            }
            $this->orderHistoryService->create(["order_id" => $id, "user_id" => null, "description" => $message]);
            return response()->json(["message" => "Update order successful"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Can't update order"], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function me()
    {
        $listStatus = [0,1,2,3,4,5,6,7,8];
        $status = request()->query('status');
        $perPage = request()->query('per_page');
        if(!in_array((int)$status,$listStatus)){
            $status=null;
        }
        if(!is_int((int)$perPage) || $perPage < 0){
            $perPage = 10;
        }
       
        $user = $this->userRepository->find(request()->user()->id);
        if (!$user) throw  new UnauthorizedException('Unauthorized!');
        $orders = $user->orders()->when(
            $status !== null,
            function ($query) use ($status) {
            
                $query->where('status', $status);
            }
        )->with(['orderHistory'])->orderBy('created_at', 'desc')->paginate($perPage);
        return 
        [
            'paginator' => $this->paginate($orders),
            'data' => OrdersCollection::collection(
                $orders->items()
            ),
        ];
    }

    public function cancelOrder($id, $data)
    {
        $order = $this->orderRepository->find($id);
        $user = request()->user();
        if ($order->user_id != $user->id) throw new AuthorizationException('Cannot cancel order!');
        if (!$order) throw new ModelNotFoundException('Order not found');
        if ($order->status >= 3 || $order->status === 0) throw new InvalidArgumentException('Cannot cancel order!');
        $order->status = 0;
        $order->reason_cancelled = $data["reason_cancelled"];
        $order->save();
        return new OrdersCollection($order);
    }

    public function reOrder($id)
    {
        $details = $this->orderRepository->find($id)->orderDetails;
        foreach ($details as $item) {
            Cart::create([
                "user_id" => request()->user()->id,
                "product_variation_id" => $item->product_variation_id ?? null,
                "product_id" => $item->product_id ?? null,
                "quantity" => $item->quantity
            ]);
        }
    }
    public function updatePaymentStatus(int|string $id,$paymentStatus = true,$paymentMethod = 'cash_on_delivery')
    {
        $order = $this->orderRepository->find($id);
        if(!$order) throw new ModelNotFoundException('Order not found');
        if($paymentStatus){
            $order->payment_status = 'paid'; 
        }else{
            $order->payment_status = 'not_yet_paid'; 
        }
        $order->payment_method = $paymentMethod;
        $order->save();
        return $order;
    }
}
