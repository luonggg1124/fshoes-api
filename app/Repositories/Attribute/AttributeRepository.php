<?php
namespace App\Repositories\Attribute;

use App\Models\Attribute;
use App\Repositories\BaseRepository;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    public function __construct(Attribute $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}