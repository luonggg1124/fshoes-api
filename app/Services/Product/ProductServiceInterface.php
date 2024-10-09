<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    function all();
    function findById(int|string $id);
    function productDetail(int|string $id);
    function thisWeekProducts();
    function bestSellingProducts();
    function shopBySports();
    function productAttribute(int|string $id);
    function createAttributeValues(int $id,string $attributeName,array $values = []);
    function create(array $data,array $options = []);
    public function updateStatus(string|int|bool $status,int|string $id);
    public function update(int|string $id, array $data,array $options=[]);
    function productWithTrashed();
    public function productTrashed();
    public function restore(int|string $id);
    function findProductTrashed(int|string $id);
    public function destroy(int|string $id);
}
