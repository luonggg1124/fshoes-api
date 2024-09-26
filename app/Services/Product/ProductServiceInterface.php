<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    function all();
    function findById(int|string $id);
    function create(array $data,array $options = []);
    public function createVariation(array $data = [],array $options = []);
    public function updateVariation(int|string $id, array $data = [], array $options = []);

}
