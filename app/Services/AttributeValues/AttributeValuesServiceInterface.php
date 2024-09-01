<?php

namespace App\Services\AttributeValues;

interface AttributeValuesServiceInterface
{
    public function getAllAttributeValue();
    public function getAttributeValueById($id);
    public function createAttributeValue(array $data);
    public function updateAttributeValue($id, array $data);
    public function deleteAttributeValue($id);
    public function restoreAttributeValue($id);
    public function forceDeleteAttributeValue($id);
}