<?php
namespace App\Services\ProductVariations;
use App\Repositories\Products\ProductRepositoryInterface;



class ProductVariationsService implements ProductVariationsServiceInterface{

    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository) {
        $this->productRepository = $productRepository;
    }
    public function getAllProductVariations(){

    }
    public function getProductVariationsById($id){

    }
    public function createProductVariations(array $data){

    }
    public function updateProductVariations($id, array $data){

    }
    public function deleteProductVariations($id){

    }
    public function restoreProductVariations($id){

    }
    public function forceDeleteProductVariations($id){

    }
}