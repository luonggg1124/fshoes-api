<?php

namespace App\Repositories\Product\Variation;

use App\Models\ProductVariations;
use App\Repositories\BaseRepository;


class VariationRepository extends BaseRepository implements VariationRepositoryInterface
{
    public function __construct(ProductVariations $model)
    {
        parent::__construct($model);
    }
}
