<?php
namespace App\Services\ProductImages;

use App\Services\ProductImages\ProductImagesServiceInterface;
use App\Repositories\ProductImages\ProductImagesRepositoryInterface;



class ProductImagesService implements ProductImagesServiceInterface{

    protected $productImagesRepository;
    public function __construct(ProductImagesRepositoryInterface $productImagesRepository) {
        $this->productImagesRepository = $productImagesRepository;
    }
    public function getAllImage(){

    }
    public function getImageById($id){

    }
    public function createImage(array $data){

    }
    public function deleteImage($id){

    }
}