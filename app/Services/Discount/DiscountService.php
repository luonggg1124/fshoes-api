<?php

namespace App\Services\Discount;

use App\Repositories\Discount\DiscountRepositoryInterface;



class DiscountService implements DiscountServiceInterface
{
    public function __construct(
        protected DiscountRepositoryInterface  $repository

    )
    {
    }

    public function addSale(){

    }

}
