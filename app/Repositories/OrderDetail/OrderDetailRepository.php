<?php
namespace App\Repositories\OrderDetail;
use App\Models\OrderDetails;
use App\Repositories\BaseRepository;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;

class OrderDetailRepository extends BaseRepository implements OrderDetailRepositoryInterface  {
    public function __construct(OrderDetails $model)
    {
        parent::__construct($model);
    }
}