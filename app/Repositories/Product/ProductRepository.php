<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;


class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(
        Product                  $model,
    )
    {
        parent::__construct($model);
    }

    public function findBySlugOrId(string $column, string $value)
    {
        $product = $this->model->query()->when(
            $column === 'id',
            function ($query) use ($value) {
                $query->where('id', $value);
            },
            function ($query) use ($value) {
                $query->where('slug', $value);
            }
        )->first();
        return $product;
    }





}
