<?php

namespace App\Services\ProductImages;

interface ProductImagesServiceInterface
{
    public function getAllImage();
    public function getImageById($id);
    public function createImage(array $data);
    public function deleteImage($id);
}