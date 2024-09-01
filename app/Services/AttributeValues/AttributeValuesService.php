<?php
namespace App\Services\AttributeValues;

use App\Services\AttributeValues\AttributeValuesServiceInterface;
use App\Repositories\AttributeValues\AttributeValuesRepositoryInterface;



class AttributeValuesService implements AttributeValuesServiceInterface{
    protected $attributeValueRepository;
    public function __construct(AttributeValuesRepositoryInterface $attributeValueRepository) {
        $this->attributeValueRepository = $attributeValueRepository;
    }
    public function getAllAttributeValue(){

    }
    public function getAttributeValueById($id){

    }
    public function createAttributeValue(array $data){

    }
    public function updateAttributeValue($id, array $data){

    }
    public function deleteAttributeValue($id){

    }
    public function restoreAttributeValue($id){

    }
    public function forceDeleteAttributeValue($id){

    }
}