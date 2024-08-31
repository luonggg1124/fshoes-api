<?php

namespace App\Jobs;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadProductImage implements ShouldQueue
{
    use Queueable;
    protected $image;
    protected $productId;
    /**
     * Create a new job instance.
     */
    public function __construct(UploadedFile $image, $productId) // Nhận $image là UploadedFile
    {
        $this->image = $image;
        $this->productId = $productId;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cloudinaryImage = new Cloudinary();
        $cloudinaryImage =$this->image->storeOnCloudinary('products');
        $url = $cloudinaryImage->getSecurePath();
        $public_id = $cloudinaryImage->getPublicId();
        ProductImage::insert([
            "product_id"=>$this->productId,
            "image_url"=>$url,
            "alt_text"=>"Product Image",
            "id_image"=>$public_id
        ]);
    }
}
