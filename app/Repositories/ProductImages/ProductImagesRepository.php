<?php

namespace App\Repositories\Products;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Products\ProductRepositoryInterface;

class ProductImagesRepository implements ProductRepositoryInterface{
    protected $productImages;
    public function __construct(ProductImage $productImages){
        $this->productImages = $productImages;
    }

    public function all(){
        return $this->productImages->withTrashed()->get();
    }

    public function paginate($rowPerPage){
        return $this->productImages->withTrashed()->paginate($rowPerPage);
    }

    public function findById($id){
        return $this->productImages->withTrashed()->find($id);
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

    public function forceDelete($id){
        return $this->productImages->withTrashed()->find($id)->forceDelete(); 
    }
    
    public function restore($id){
        return $this->productImages->withTrashed()->find($id)->restore(); 
    }
}

