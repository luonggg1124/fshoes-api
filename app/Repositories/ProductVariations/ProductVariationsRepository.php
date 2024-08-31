<?php

namespace App\Repositories\ProductVariations;
use App\Models\ProductVariations;
use App\Repositories\Products\ProductRepositoryInterface;

class ProductVariationsRepository implements ProductRepositoryInterface{
    protected $productVariations;
    public function __construct(ProductVariations $productVariations){
        $this->productVariations = $productVariations;
    }

    public function all(){
        return $this->productVariations->withTrashed()->get();
    }
    
    public function paginate($rowPerPage){
        return $this->productVariations->withTrashed()->paginate($rowPerPage);
    }

    public function findById($id){
        return $this->productVariations->withTrashed()->find($id);
    }

    public function create(array $data){
        return $this->productVariations->create($data);
    }

    public function update($id, array $data){
        return $this->productVariations->find($id)->update($data);
    }

    public function delete($id){
        return $this->productVariations->find($id)->delete();
    }

    public function forceDelete($id){
        return $this->productVariations->withTrashed()->find($id)->forceDelete(); 
    }
    
    public function restore($id){
        return $this->productVariations->withTrashed()->find($id)->restore(); 
    }
}

