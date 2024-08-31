<?php

namespace App\Repositories\Attribute;
use App\Models\Attribute;

class AttributesRepository implements AttributesRepositoryInterface{
    protected $attributes;
    public function __construct(Attribute $attributes){
        $this->attributes = $attributes;
    }

    public function all(){
        return $this->attributes->withTrashed()->get();
    }

    public function paginate($rowPerPage){
        return $this->attributes->withTrashed()->paginate($rowPerPage);
    }

    public function findById($id){
        return $this->attributes->withTrashed()->find($id);
    }

    public function findByName($name){
        return $this->attributes->withTrashed()->where('name' , $name)->first();
    }

    public function create(array $data){
        
    }

    public function update($id, array $data){
        return $this->attributes->find($id)->update($data);
    }

    public function delete($id){
        return $this->attributes->find($id)->delete();
    }

    public function forceDelete($id){
        return $this->attributes->withTrashed()->find($id)->forceDelete(); 
    }
    
    public function restore($id){
        return $this->attributes->withTrashed()->find($id)->restore(); 
    }
}

