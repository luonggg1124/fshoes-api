<?php

namespace App\Repositories\Attribute;

use App\Repositories\BaseRepositoryInterface;

interface AttributesRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete($id);
    public function restore($id);
    public function paginate($rowPerPage);
    public function findByName($name);
}
