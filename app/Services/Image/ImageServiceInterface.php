<?php

namespace App\Services\Image;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    public function all();
    public function create(UploadedFile $file,$folder = '');
    public function destroy(int|string $id);
}
