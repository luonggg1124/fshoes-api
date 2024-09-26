<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;


class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
