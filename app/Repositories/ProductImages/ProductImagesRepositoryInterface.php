<?php

namespace App\Repositories\ProductImages;

use App\Repositories\BaseRepositoryInterface;


interface ProductImagesRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete($id);
    public function restore($id);
    public function paginate($rowPerPage);
}
