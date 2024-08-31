<?php

namespace App\Services\Products;

interface ProductServiceInterface
{
    public function getAllProducts();
    public function getProductById($id);
    public function createProduct(array $data);
    public function updateProduct($id, array $data);
    public function deleteProduct($id);
    public function restoreProduct($id);
    public function forceDeleteProduct($id);
}