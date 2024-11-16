<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariations;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Post\PostRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Review\ReviewRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct(
        private OrderRepository   $orderRepository,
        private ProductVariations $productVariations,
        private ProductRepository $productRepository,
        private UserRepository    $userRepository,
        private PostRepository    $postRepository,
        private ReviewRepository   $reviewRepository,
    )
    {

    }

    public function overall(Request $request)
    {
        return response()->json([
            "total_amount"=>$this->orderRepository->query()->sum('total_amount'),
            "total_user"=>$this->userRepository->query()->count('id'),
            "total_product"=>$this->productRepository->query()->count('id'),
             "total_order"=>$this->orderRepository->all()->count('id'),
            "recent_order"=>$this->orderRepository->query()->orderBy('id' , 'DESC')->take(5)->get()->map(function($item){
                    $price_input = 0;
                    foreach($item->orderDetails ?? [] as $detail){
                        $price_input += (($detail->product->import_price ?? $detail->variation->import_price) * $detail->quantity);
                    }
                    return [
                            "user_id"=>$item->user->name,
                            "total_amount"=>$item->total_amount  - $price_input >= 0 ? $item->total_amount  - $price_input : 0 ,
                        ];
            }),
            "recent_review"=>$this->reviewRepository->query()->orderBy('id' , 'DESC')->take(5)->get()->map(function($item){
                return[
                    "user_id"=>$item->user->name,
                    "title"=>$item->title,
                    "rating"=>$item->rating,
                ];
            }),
        ],200);
    }

    public function order()
    {
        $countOrder = 0;
        $total_profit = 0;
        $orderFails = 0;
        $orderSuccess = 0;
        $orderReturn = 0;
        $orderInProcess = 0;
        return response()->json([
            "data" => $this->orderRepository->all()->map(function ($order) use (&$countOrder, &$total_profit, &$orderFails, &$orderReturn, &$orderSuccess, &$orderInProcess) {
                $countOrder++;
                $total_profit += floatval($order->total_amount);
                if ($order->status == 0) $orderFails++;
                else if ($order->status == 4) $orderSuccess++;
                else if ($order->status == 7) $orderReturn++;
                else $orderInProcess++;
                return [
                    "total_amount" => $order->total_amount,
                    "payment_method" => $order->payment_method,
                    "create_at" => Carbon::parse($order->created_at)->toDateTimeString(),
                    "status" => $order->status,
                ];
            }),
            "total_order" => $countOrder,
            "total_profit" => $total_profit,
            "order_success" => $orderSuccess,
            "order_fails" => $orderFails,
            "order_return" => $orderReturn,
            "order_in_process" => $orderInProcess,
            "percentage_return" => round($orderReturn / ($orderReturn + $orderFails + $orderInProcess + $orderSuccess) * 100),
            "percentage_fail" => round($orderFails / ($orderReturn + $orderFails + $orderInProcess) * 100)
        ], 200);
    }

    public function product()
    {
        $countProduct = 0;
        $totalStockQty = 0;
        $totalSold = 0;
        return response()->json([
            "data" => $this->productRepository->all()->map(function ($product) use (&$countProduct, &$totalStockQty, &$totalSold) {
                $countProduct++;
                $totalSoldPerVariation = 0;
                $totalStockPerVariation = 0;
                $arr = [
                    "name" => $product->name,
                    "image_url"=>$product->image_url,
                    "price" => $product->price,
                    "variant" => $product->variations->map(function ($variation) use (&$totalStockPerVariation, &$totalSoldPerVariation) {
                        $totalSoldPerVariation += floatval($variation->qty_sold);
                        $totalStockPerVariation += floatval($variation->stock_qty);
                        return [
                            "name" => $variation->name,
                            "price" => $variation->price,
                        ];
                    }),
                    "stock_qty" => sizeof($product->variations) == 0 ? $product->stock_qty : $totalStockPerVariation,
                    "qty_sold" => sizeof($product->variations) == 0 ? $product->qty_sold : $totalSoldPerVariation,
                    "count_interested"=>sizeof($product->likedBy),
                    "stars"=>round($this->reviewRepository->query()->where('product_id', $product->id)->average('rating') ,1),
                ];

                $totalStockQty += (sizeof($product->variations) == 0 ? $totalStockPerVariation : $product->stock_qty);
                $totalSold += (sizeof($product->variations) == 0 ? $totalSoldPerVariation : $product->qty_sold);
                return $arr;
            }),
            "sold" => $totalSold,
            "in_stock" => $totalStockQty,
        ], 200);
    }

     public function user()
    {
        return response()->json(
            
                $this->userRepository->all()->map(function ($user) {
                    return [
                        "id"=>$user->id,
                        "name" => $user->name,
                        "total_orders"=>$this->orderRepository->query()->where("user_id",$user->id)->count(),
                        "spend_money"=>$this->orderRepository->query()->where("user_id",$user->id)->sum("total_amount"),
                        "review"=>sizeof($user->reviews),
                        "posts"=>$this->postRepository->query()->where("author_id",$user->id)->count(),
                    ];
                })
            
            ,
            200);
    }


    public function review()
    {
        $five =0;
        $four =0;
        $three =0;
        $two =0;
        $one =0;
        return response()->json([
           "data" =>  $this->reviewRepository->all()->map(function ($review) use (&$five, &$four, &$three, &$two, &$one) {
               if($review->rating == 5) $five++;
               else if($review->rating == 4) $four++;
               else if($review->rating == 3) $three++;
               else if($review->rating == 2) $two++;
               else if($review->rating == 1) $one++;
               return [
                   "id"=>$review->id,
                   "user"=>$review->user->name,
                   "product_id"=>$review->product_id,
                   "title"=>$review->title,
                   "content"=>$review->content,
                   "rating"=>$review->rating,
               ];
           }),
            "five"=>$five,
            "four"=>$four,
            "three"=>$three,
            "two"=>$two,
            "one"=>$one,
        ],200);
    }
}
