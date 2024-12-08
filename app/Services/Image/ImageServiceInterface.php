<?php

namespace App\Services\Image;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    public function all();
    public function createMany(array $images,string $folder = '');
    public function create(UploadedFile $file,string $folder = '');
    public function destroy(int|string $id);
    function destroyMany(array $ids);
}
