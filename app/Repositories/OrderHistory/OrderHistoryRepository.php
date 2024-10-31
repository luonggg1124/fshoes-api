<?php
namespace App\Repositories\OrderHistory;
use App\Models\OrderHistory;
use App\Repositories\BaseRepository;
use App\Repositories\OrderHistory\OrderHistoryRepositoryInterface;

class OrderHistoryRepository extends BaseRepository implements OrderHistoryRepositoryInterface  {
    public function __construct(OrderHistory $model)
    {
        parent::__construct($model);
    }
}
