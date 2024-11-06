<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariations;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct(
        private OrderRepository   $orderRepository,
        private ProductVariations $productVariations,
        private ProductRepository $productRepository,
    )
    {

    }

    public function statistics()
    {

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
                ];

                $totalStockQty +=(sizeof($product->variations) ==0 ?  $totalStockPerVariation : $product->stock_qty );
                $totalSold += (sizeof($product->variations) ==0 ?  $totalSoldPerVariation : $product->qty_sold );
                return $arr;
            }),
            "sold"=>$totalSold,
            "in_stock" => $totalStockQty,
        ], 200);
    }
    public function user()
    {

    }
    public function post(){

    }
}
