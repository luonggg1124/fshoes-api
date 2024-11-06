<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
    )
    {

    }
    public function statistics(){

    }
    public function profit(){
//        return Carbon::parse($this->orderRepository->all()[0]->created_at)->month;
    }
    public function bestSelling()
    {

    }
}
