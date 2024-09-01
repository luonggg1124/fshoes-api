<?php

namespace App\Services\ProductVariations;

interface ProductVariationsServiceInterface
{
    public function getAllProductVariations();
    public function getProductVariationsById($id);
    public function createProductVariations(array $data);
    public function updateProductVariations($id, array $data);
    public function deleteProductVariations($id);
    public function restoreProductVariations($id);
    public function forceDeleteProductVariations($id);
}