<?php

namespace App\Services\Sale;

interface SaleServiceInterface
{
    function all();
    function show(int|string $id);
    function store(array $data, array $options = []);
    function update(int|string $id,array $data, array $options = []);
    function destroy(int|string $id);
    function switchActive(int|string $id,bool|null $active);
}
