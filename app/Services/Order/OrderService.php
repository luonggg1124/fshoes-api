<?php

namespace App\Services\Order;


use App\Http\Traits\Paginate;
use App\Jobs\PaidOrder;
use Exception;
use App\Models\Cart;
use App\Models\Voucher;
use App\Mail\CreateOrder;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrdersCollection;
use Illuminate\Validation\UnauthorizedException;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\OrderHistory\OrderHistoryServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class OrderService implements OrderServiceInterface
{
    use Paginate;
    protected $cacheTag = 'orders';
    private array $relations = ['products', 'variations', 'statistics'];
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
            throw new ModelNotFoundException(__('messages.error-not-found'));
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
                if ($item->stock_qty - $detail["quantity"] <= 0) {
                    if ($detail["product_id"]) {
                        $message = __('messages.cart.product_word') . $item->name . __('messages.cart.out_of_stock') . $item->stock_qty .  __('messages.cart.units');
                    } else  $message = __('messages.cart.variations_word') . $item->name . __('messages.cart.out_of_stock') . $item->stock_qty .  __('messages.cart.units');
                }
            }

            if (isset($data["voucher_id"])) {
                $voucher = Voucher::find($data["voucher_id"]);
                $voucher->quantity--;
                $voucher->users()->attach(request()->user()->id);
                if ($voucher->quantity < 0) return response()->json(["message" => __('messages.voucher.error-voucher')], 500);
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

                if ($item->currentSale()) {
                    $item->sales()->updateExistingPivot($item->currentSale()->id, [
                        'quantity' => $item->currentSale()->pivot->quantity + $detail["quantity"],
                    ]);
                }


                $item->save();
            }
            if (request()->user()) {
                $this->orderHistoryService->create(["order_id" => $order->id, "user_id" => null, "description" => request()->user()->name . " created order"]);
            } else $this->orderHistoryService->create(["order_id" => $order->id, "user_id" => null, "description" => "Guess" . " created order"]);

            if (isset($data['cart_ids'])) {
                foreach ($data['cart_ids'] as $id) {
                    $this->cartRepository->delete($id);
                }
            }

            dispatch(new \App\Jobs\CreateOrder($order->id, $order->receiver_email))->delay(now()->addSeconds(2));
            Cache::tags([$this->cacheTag, ...$this->relations])->flush();
            return response()->json([
                "message" => __('messages.created-success'),
                'order' => $order
            ], 201);
        } catch (Exception $e) {
            logger()->error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int|string $id, array $data, array $option = [])
    {
        if (isset($data["status"]) && $data["status"] == "0" && !isset($data["reason_cancelled"])) {
            return response()->json(["message" => __('messages.order.error-specific')], 403);
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
                    $message = __('messages.order.error-confirmed');
                    break;
                case 3:
                    $message = __('messages.order.error-delivered');
                    break;
                case 4:
                    $message = __('messages.order.error-was-delivered');
                    break;
                case 5:
                    $message = __('messages.order.error-processing');
                    break;
                case 6:
                    $message = __('messages.order.error-returned-processing');
                    break;
                case 7:
                    $message = __('messages.order.error-returned');
                    break;
            }

            $this->orderHistoryService->create(["order_id" => $id, "user_id" => null, "description" => $message]);
            return response()->json(["message" => __('messages.update-success')], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => __('messages.order.error-can-not-order')], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function me()
    {
        $listStatus = [0, 1, 2, 3, 4, 5, 6, 7, 8];
        $status = request()->query('status');
        $perPage = request()->query('per_page');
        if (!in_array((int)$status, $listStatus)) {
            $status = null;
        }
        if (!is_int((int)$perPage) || $perPage < 0) {
            $perPage = 10;
        }

        $user = $this->userRepository->find(request()->user()->id);
        if (!$user) throw  new UnauthorizedException(__('messages.order.error-order'));
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
        if ($order->user_id != $user->id) throw new AuthorizationException(__('messages.order.error-can-not-order'));
        if (!$order) throw new ModelNotFoundException(__('messages.error-not-found'));
        if ($order->status >= 3 || $order->status === 0) throw new InvalidArgumentException(__('messages.order.error-can-not-order'));
        $order->status = 0;
        $order->reason_cancelled = $data["reason_cancelled"];
        $voucher = $order->voucher;
        if ($voucher) {
            $user->voucherUsed()->detach([$voucher->id]);
        }
        $order->save();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
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
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
    }
    public function updatePaymentStatus(int|string $id, $paymentStatus = true, $paymentMethod = 'cash_on_delivery')
    {
        $order = $this->orderRepository->find($id);
        if (!$order) throw new ModelNotFoundException(__('messages.error-not-found'));
        if ($paymentStatus) {
            $order->payment_status = 'paid';
        } else {
            $order->payment_status = 'not_yet_paid';
        }
        $order->payment_method = $paymentMethod;
        $order->save();
        if($order->payment_status){
            dispatch(new PaidOrder($order->id,$order->receiver_email))->delay(now()->addSeconds(2));
        }
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return $order;
    }
}
