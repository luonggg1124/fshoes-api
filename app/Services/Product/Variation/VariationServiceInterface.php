<?php

namespace App\Services\Product\Variation;

interface VariationServiceInterface
{
    function index(int|string $pid);
    function create(int|string $pid,array $data,array $options = [
        'values' => []
    ]);
    function createMany(int|string $pid,array $data);
}
