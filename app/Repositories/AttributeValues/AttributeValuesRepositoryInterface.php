<?php

namespace App\Repositories\AttributeValues;

use App\Repositories\BaseRepositoryInterface;

interface AttributeValuesRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete($id);
    public function restore($id);
    public function paginate($rowPerPage);
}
