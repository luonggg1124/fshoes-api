<?php
namespace App\Services\Attribute;

use App\Repositories\Attribute\AttributesRepositoryInterface;
use App\Services\Attribute\AttributesServiceInterface;



class AttributesService implements AttributesServiceInterface{
    protected $attributeRepository;
    public function __construct(AttributesRepositoryInterface $attributeRepository) {
        $this->attributeRepository = $attributeRepository;
    }
    public function getAllAttribute(){

    }
    public function getAttributeById($id){

    }
    public function createAttribute(array $data){

    }
    public function updateAttribute($id, array $data){

    }
    public function deleteAttribute($id){

    }
    public function restoreAttribute($id){

    }
    public function forceDeleteAttribute($id){

    }
}