<?php

namespace App\Services\Discount;

interface DiscountServiceInterface
{
    function all();

    function store(array $data, array $options = []);
    function update(int|string $id,array $data, array $options = []);
}
