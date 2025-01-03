<?php

namespace App\Services\Statistics;

use App\Http\Resources\OrdersCollection;
use App\Http\Resources\Product\BestSellingProductResource;
use App\Http\Traits\HandleTime;
use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Review\ReviewRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;



use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticsService implements StatisticsServiceInterface
{
    use HandleTime;
    protected $allQueryUrl;
    protected $cacheTag = 'statistics';
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected OrderDetailRepositoryInterface $orderDetailRepository,
        protected ReviewRepositoryInterface $reviewRepository,
        protected ProductRepositoryInterface $productRepository,
    ) {
        $this->allQueryUrl = http_build_query(request()->query());
    }
    public function overall()
    {
        return Cache::tags([$this->cacheTag])->remember('statistics/overall?' . $this->allQueryUrl, 60, function () {
            $startDate = request()->query('from');
            $endDate = request()->query('to');

            if (!$this->isValidTime($startDate)) {
                $startDate = $this->oneWeekAgo();
            }

            if (!$this->isValidTime($endDate)) {
                $endDate = $this->now();
            }
            if (!$this->isGreaterDate($startDate, $endDate)) {
                $startDate = $this->oneWeekAgo();
                $endDate = $this->now();
            }
            $totalNewUsers = $this->statisticsTotalAndPercentage($startDate, $endDate, $this->userRepository);
            $totalNewProducts = $this->statisticsTotalAndPercentage($startDate, $endDate, $this->productRepository);
            $totalNewOrders = $this->statisticsTotalAndPercentage($startDate, $endDate, $this->orderRepository);
            $totalAmountOrder = $this->totalAvenueOrder();
            return [
                'users' => $totalNewUsers,
                'products' => $totalNewProducts,
                'orders' => $totalNewOrders,
                'total_amount_orders' => $totalAmountOrder
            ];
        });
    }
    public function statisticsTotalAndPercentage($from, $to, BaseRepositoryInterface|BaseRepository $repository)
    {
        $count = $this->countByDateForStatistics($from, $to, $repository);
        $countAll = $repository->query()->count();
        $totalExceptNew = $countAll - $count;
        $percentage = 0;
        if ($totalExceptNew == 0 && $count != 0) {
            $percentage = 100;
        } else if ($count > 0) {
            $percentage = ($count / $totalExceptNew) * 100;
        }else if($count == 1){
            $percentage = 0;
        }

        return [
            'total' => $count,
            'percentage' => $percentage
        ];
    }
    private function countByDateForStatistics(string $from = '', string $to = '', BaseRepositoryInterface|BaseRepository $repository)
    {
        $count = $repository->query()->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ]);
        })->count();
        return $count;
    }

    private function getByDateForStatistics(string $from = '', string $to = '', BaseRepositoryInterface|BaseRepository $repository, string $orderByColumn = 'created_at', string $direction = 'asc')
    {
        $records = $repository->query()->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ]);
        })->orderBy($orderByColumn, $direction)->get();
        return $records;
    }
    public function totalAvenueOrder(){
        $startDate = request()->query('from');
            $endDate = request()->query('to');

            if (!$this->isValidTime($startDate)) {
                $startDate = $this->oneWeekAgo();
            }

            if (!$this->isValidTime($endDate)) {
                $endDate = $this->now();
            }
            if (!$this->isGreaterDate($startDate, $endDate)) {
                $startDate = $this->oneWeekAgo();
                $endDate = $this->now();
            }
        $sum = $this->orderRepository->query()->where('status','>',3)->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
            ]);
        })->sum('total_amount');
        return $sum;
    }
    private function calculatorSumRecordsGetByDateForStatistics(string $column = 'id', string $from = '', string $to = '', BaseRepositoryInterface|BaseRepository $repository)
    {
        $sum = $repository->query()->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ]);
        })->sum($column);
        return $sum;
    }
    public function ordersForDiagram()
    {
        return Cache::tags([$this->cacheTag])->remember('orders/for/diagram?' . $this->allQueryUrl, 60, function () {
            $startDate = request()->query('from');
            $endDate = request()->query('to');

            if (!$this->isValidTime($startDate)) {
                $startDate = $this->oneWeekAgo();
            }

            if (!$this->isValidTime($endDate)) {
                $endDate = $this->now();
            }
            if (!$this->isGreaterDate($startDate, $endDate)) {
                $startDate = $this->oneWeekAgo();
                $endDate = $this->now();
            }
            $orders = $this->getByDateForStatistics($startDate, $endDate, $this->orderRepository);
            return [
                'orders' => OrdersCollection::collection($orders)
            ];
        });
    }

    public function productBestSelling()
    {
            
        return Cache::tags([$this->cacheTag])->remember('product/best_selling?' . $this->allQueryUrl, 60, function () {
            $startDate = request()->query('from');
            $endDate = request()->query('to');

            if (!$this->isValidTime($startDate)) {
                $startDate = $this->oneWeekAgo();
            }
            if (!$this->isValidTime($endDate)) {
                $endDate = $this->now();
            }
            if (!$this->isGreaterDate($startDate, $endDate)) {
                $startDate = $this->oneWeekAgo();
                $endDate = $this->now();
            }
            $bestSellingProducts = $this->orderDetailRepository->query()->with('product')
            ->select(
                DB::raw('COALESCE(order_details.product_id, product_variations.product_id) as product_id'),
                DB::raw('SUM(order_details.quantity) as total_sold_quantity')
            )
            ->leftJoin('product_variations', 'order_details.product_variation_id', '=', 'product_variations.id')
                ->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [
                        Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                        Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
                    ]);
                })->groupBy('product_id')
                ->orderByDesc('total_sold_quantity')
                ->get();
         
            return BestSellingProductResource::collection($bestSellingProducts);
        });
    }

    public function revenueOfYear()
    {
        return Cache::tags([$this->cacheTag])->remember('revenue_of_year?' . $this->allQueryUrl, 60, function () {
            $year = request()->query('year');
            $isValidYear = $this->isValidYear($year);
            if (!$isValidYear || !$year) {
                $year = now()->year;
            }
            $months = range(1, 12);
            $revenues = [];
            foreach ($months as $month) {
                $revenues[] = $this->revenueOfMonths($month, $year, true);
            }

            return [...$revenues];
        });
    }
    public function revenueOfMonths($month = 1, $year = null, bool $intval = false)
    {
        $year = $year ?? now()->year;
        $sum = $this->orderRepository->query()->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('total_amount');
        if ($intval) {
            return intval($sum);
        }
        return $sum;
    }

    public function countWaitingConfirmOrders()
    {
        return Cache::tags([$this->cacheTag])->remember('waitingConfirmOrders?' . $this->allQueryUrl, 60, function () {
            $count = $this->orderRepository->query()->where('status', 1)->count();
            return $count;
        });
    }
}
