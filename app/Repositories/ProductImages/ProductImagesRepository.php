<?php

namespace App\Repositories\ProductImages;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\ProductImages\ProductImagesRepositoryInterface;

class ProductImagesRepository implements ProductImagesRepositoryInterface{
    protected $productImages;
    public function __construct(ProductImage $productImages){
        $this->productImages = $productImages;
    }

    public function all(){
        return $this->productImages->get();
    }


    public function findById($id){
        return $this->productImages->find($id);
    }

    public function create(array $data){
        return $this->productImages->create($data);
    }

    public function update($id, array $data){
        return $this->productImages->find($id)->update($data);
    }

    public function delete($id){
        return $this->productImages->find($id)->delete();
    }

}

