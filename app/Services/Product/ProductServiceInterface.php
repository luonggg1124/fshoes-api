<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    function all();
    function findById(int|string $id);
    function create(array $data,array $option = []);
}
