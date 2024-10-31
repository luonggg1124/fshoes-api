<?php

namespace App\Services\Discount;

interface DiscountServiceInterface
{
    function all();
    function show(int|string $id);
    function store(array $data, array $options = []);
    function update(int|string $id,array $data, array $options = []);
    function destroy(int|string $id);
}
