<?php

namespace App\Repositories\AttributeValues;
use App\Models\AttributeValue;

class AttributeValuesRepository implements AttributeValuesRepositoryInterface{
    protected $attributeValues;
    public function __construct(AttributeValue $attributeValues){
        $this->attributeValues = $attributeValues;
    }
    public function all(){
        return $this->attributeValues->withTrashed()->get();
    }

    public function paginate($rowPerPage){
        return $this->attributeValues->withTrashed()->paginate($rowPerPage);
    }

    public function findById($id){
        return $this->attributeValues->withTrashed()->find($id);
    }

    public function create(array $data){
        $attribute_id = $data['attribute_id'];
        foreach ($data['values'] as $value){
            $this->attributeValues->insert([
                "attribute_id" => $attribute_id,
                "value"=>$value
            ]); 
        }
        return true;
    }

    public function update($id, array $data){
        return $this->attributeValues->find($id)->update($data);
    }

    public function delete($id){
        return $this->attributeValues->find($id)->delete();
    }

    public function forceDelete($id){
        return $this->attributeValues->withTrashed()->find($id)->forceDelete(); 
    }
    
    public function restore($id){
        return $this->attributeValues->withTrashed()->find($id)->restore(); 
    }
}

