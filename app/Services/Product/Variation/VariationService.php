<?php

namespace App\Services\Product\Variation;

use App\Repositories\Product\Variation\VariationRepositoryInterface;

class VariationService implements VariationServiceInterface
{
    public function __construct(
        protected VariationRepositoryInterface $repository
    )
    {

    }
}
