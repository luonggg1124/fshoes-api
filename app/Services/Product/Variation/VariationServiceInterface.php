<?php

namespace App\Services\Product\Variation;

interface VariationServiceInterface
{
    function index(int|string $pid);
    function create(int|string $pid,array $data,array $options = [
        'values' => []
    ]);
    function show(int|string $pid,int|string $id);
    function destroy(int|string $pid,int|string $id);
    function createMany(int|string $pid,array $data);
}
