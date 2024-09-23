<?php

namespace App\Http\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Cloudinary
{
    public function uploadImageCloudinary(UploadedFile $file,string $folder = ''):?array
    {
        $upload = $file->storeOnCloudinary($folder);
        $path = $upload->getSecurePath();
        $public_id = $upload->getPublicId();
        return [
            'path' => $path,
            'public_id' => $public_id,
        ];
    }

    public function updateImageCloudinary(UploadedFile $file,string $folder = ''):?array
    {

    }
    public function deleteImageCloudinary(string $public_id):void
    {
        if(Storage::disk('cloudinary')->fileExists($public_id)){
            cloudinary()->destroy($public_id);
        }
    }
}
