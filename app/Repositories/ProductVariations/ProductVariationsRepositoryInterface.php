<?php

namespace App\Repositories\ProductVariations;

use App\Repositories\BaseRepositoryInterface;



interface ProductVariationsRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete($id);
    public function restore($id);
    public function paginate($rowPerPage);
}
