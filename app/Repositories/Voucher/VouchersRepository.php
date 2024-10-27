<?php

namespace App\Repositories\Voucher;

use App\Models\Voucher;
use App\Repositories\BaseRepository;
use App\Repositories\Voucher\VouchersRepositoryInterface;


class VouchersRepository extends BaseRepository implements VouchersRepositoryInterface
{
    public function __construct(Voucher $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
