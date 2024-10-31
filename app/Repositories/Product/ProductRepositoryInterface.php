<?php

namespace App\Repositories\Product;
use App\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlugOrId(string $column, string $value);


}
