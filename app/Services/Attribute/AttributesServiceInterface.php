<?php

namespace App\Services\Attribute;

interface AttributesServiceInterface
{
    public function getAllAttribute();
    public function getAttributeById($id);
    public function createAttribute(array $data);
    public function updateAttribute($id, array $data);
    public function deleteAttribute($id);
    public function restoreAttribute($id);
    public function forceDeleteAttribute($id);
}