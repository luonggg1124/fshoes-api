<?php

namespace App\Repositories\Products;
use App\Models\Product;
use App\Repositories\Products\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface{
    protected $products;
    public function __construct(Product $products){
        $this->products = $products;
    }

    public function all(){
        return $this->products->withTrashed()->get();
    }

    public function paginate($rowPerPage){
        return $this->products->withTrashed()->paginate($rowPerPage);
    }

    public function findById($id){
        return $this->products->withTrashed()->find($id);
    }

    public function create(array $data){
        return $this->products->create($data);
    }

    public function update($id, array $data){
        return $this->products->find($id)->update($data);
    }

    public function delete($id){
        return $this->products->where('id' , $id)->delete();
    }

    public function forceDelete($id){
        $product =  $this->products->withTrashed()->where('id', $id)->first();
        $product->images()->delete();
        return  $product->forceDelete();
      
    }
    
    public function restore($id){
        return $this->products->withTrashed()->find($id)->restore(); 
    }
}

