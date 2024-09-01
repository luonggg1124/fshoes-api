<?php

namespace App\Repositories\Products;

use App\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete($id);
    public function restore($id);
    public function paginate($rowPerPage);
}
