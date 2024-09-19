<?php

namespace App\Repositories\Product;
use App\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlugOrId(string $column, string $value);
    public function createVariations(array $data = []);
    public function updateVariations(int|string $id, array $data = []);
    public function createImage(array $data = []);
    public function updateImage(int|string $id, array $data = []);

}
