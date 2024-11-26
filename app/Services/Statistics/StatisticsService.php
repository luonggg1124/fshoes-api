<?php 
namespace App\Services\Statistics;

use App\Http\Resources\OrdersCollection;
use App\Http\Resources\Product\BestSellingProductResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\HandleTime;
use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Review\ReviewRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;



use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService implements StatisticsServiceInterface{
    use HandleTime;
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected OrderDetailRepositoryInterface $orderDetailRepository,
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
        
        $totalNewUsers = $this->statisticsTotalAndPercentage($startDate, $endDate,$this->userRepository);
        $totalNewProducts = $this->statisticsTotalAndPercentage($startDate, $endDate,$this->productRepository);
        $totalNewOrders = $this->statisticsTotalAndPercentage($startDate, $endDate,$this->orderRepository);
        $totalAmountOrder = $this->calculatorSumRecordsGetByDateForStatistics('total_amount',$startDate, $endDate,$this->orderRepository);

        return [
            'users' => $totalNewUsers,
            'products' => $totalNewProducts,
            'orders' => $totalNewOrders,
            'total_amount_orders' => $totalAmountOrder
        ];
    }
    public function statisticsTotalAndPercentage($from,$to,BaseRepositoryInterface|BaseRepository $repository){
        
        $count = $this->countByDateForStatistics($from, $to, $repository);
        $countAll = $repository->query()->count();
        $totalExceptNew = $countAll - $count;
        $percentage = 0;
        if($totalExceptNew === 0){
            $percentage = 100;
        }else if($count > 0){
            $percentage = ($count / $totalExceptNew) * 100;
        }
        
        return [
            'total' => $count,
            'percentage' => $percentage
        ];
    }
    private function countByDateForStatistics(string $from = '',string $to = '',BaseRepositoryInterface|BaseRepository $repository)
    {
        $count = $repository->query()->when($from && $to, function ($q)use ($from, $to){
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ]);
        })->count();
        return $count;
    }

    private function getByDateForStatistics(string $from = '',string $to = '',BaseRepositoryInterface|BaseRepository $repository,string $orderByColumn = 'created_at',string $direction = 'asc'){
        $records = $repository->query()->when($from && $to, function ($q)use ($from, $to){
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ]);
        })->orderBy($orderByColumn,$direction)->get();
        return $records;
    }
    private function calculatorSumRecordsGetByDateForStatistics(string $column = 'id',string $from = '',string $to = '',BaseRepositoryInterface|BaseRepository $repository){
        $sum = $repository->query()->when($from && $to, function ($q)use ($from, $to){
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $from)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $to)->endOfDay()
            ]);
        })->sum($column);
        return $sum;
    }
    public function ordersForDiagram(){
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
        $orders = $this->getByDateForStatistics($startDate, $endDate,$this->orderRepository);
        return [
            'orders' => OrdersCollection::collection($orders)
        ];
    }

    public function productBestSelling(){
        $startDate = request()->query('from');
        $endDate = request()->query('to');
        
        if(!$this->isValidTime($startDate)){
            $startDate = $this->oneMonthAgo();
        }
        
        if(!$this->isValidTime($endDate)){
            $endDate = $this->now();
            
        }
        if(!$this->isGreaterDate($startDate,$endDate)){
            $startDate = $this->oneMonthAgo();
            $endDate = $this->now();
        }
        $bestSellingProducts = $this->orderDetailRepository->query()->with('product')
        ->select('product_id', DB::raw('SUM(quantity) as total_sold_quantity'))
        ->whereHas('order',function($q) use($startDate,$endDate){
            $q->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
            ]);
        })->groupBy('product_id')
        ->orderByDesc('total_sold_quantity')
        ->get();
        return BestSellingProductResource::collection($bestSellingProducts);
    }

    public function revenueOfYear(){
        $year = request()->query('year');
        $isValidYear = $this->isValidYear($year);
        if(!$isValidYear || !$year){
            $year = now()->year;
        }
        $months = range(1,12);
        $revenues = [];
        foreach($months as $month){
            $revenues[] = $this->revenueOfMonths($month, $year,true);
            
        }
       
        return [...$revenues];
    }
    public function revenueOfMonths($month = 1,$year = null,bool $intval = false)
    {
        $year = $year ?? now()->year;
        $sum = $this->orderRepository->query()->whereYear('created_at',$year)->whereMonth('created_at',$month)->sum('total_amount');
        if($intval){
            return intval($sum);
        }
        return $sum;
    }
}