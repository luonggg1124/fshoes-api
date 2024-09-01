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
        return $this->productImagesRepository->all();
    }
    public function getImageById($id){
        return $this->productImagesRepository->findById($id);

    }
    public function createImage(array $data){

    }
    public function deleteImage($id){
        return $this->productImagesRepository->delete($id);
    }
}