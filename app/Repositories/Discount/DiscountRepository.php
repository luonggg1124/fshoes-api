<?php

namespace App\Repositories\Discount;

use App\Models\Discount;
use App\Repositories\BaseRepository;


class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }
}
