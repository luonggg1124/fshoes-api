<?php 
namespace App\Services\Statistics;

use App\Http\Traits\HandleTime;
use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Review\ReviewRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;



use Carbon\Carbon;

class StatisticsService implements StatisticsServiceInterface{
    use HandleTime;
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected ReviewRepositoryInterface $reviewRepository,
        protected ProductRepositoryInterface $productRepository,
    ){}
    public function overall(){
        $startDate = request()->query('from');
        $endDate = request()->query('to');
        
        if(!$this->isValidTime($startDate)){
            $startDate = $this->oneMonthAgo();
        }
       
        if(!$this->isValidTime($endDate)){
            $endDate = $this->now();
            
        }
        if(!$this->isGreaterDate($startDate,$endDate))
        {
            $startDate = $this->oneMonthAgo();
            $endDate = $this->now();
        }
        
        $totalNewUsers = $this->countByDateForStatistics($startDate, $endDate, $this->userRepository);
        $totalNewProducts = $this->countByDateForStatistics($startDate, $endDate,$this->productRepository);
        $totalNewOrders = $this->countByDateForStatistics($startDate, $endDate,$this->orderRepository);
        $totalAmountOrder = $this->calculatorSumRecordsGetByDateForStatistics($startDate, $endDate,$this->orderRepository);

        return [
            'total_products' => $totalNewProducts,
            'total_users' => $totalNewUsers,
            'total_orders' => $totalNewOrders,
            'total_amount_orders' => $totalAmountOrder
        ];
    }
    private function countByDateForStatistics(string $from,string $to ,BaseRepositoryInterface|BaseRepository $repository)
    {
        $count = $repository->query()->whereBetween('created_at', [
            Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
            Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ])->count();
        return $count;
    }
    private function getByDateForStatistics(string $from,string $to ,BaseRepositoryInterface|BaseRepository $repository){
        $records = $repository->query()->whereBetween('created_at', [
            Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
            Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ])->get();
        return $records;
    }
    private function calculatorSumRecordsGetByDateForStatistics(string $from,string $to ,BaseRepositoryInterface|BaseRepository $repository){
        $sum = $repository->query()->whereBetween('created_at', [
            Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
            Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ])->sum('total_amount');
        return $sum;
    }
}