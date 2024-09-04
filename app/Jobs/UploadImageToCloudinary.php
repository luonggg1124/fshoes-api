<?php

namespace App\Jobs;



use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadImageToCloudinary implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string     $modelType,
        protected int|string $modelId,
        protected            $path
    )
    {

    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        $result = cloudinary()->upload($this->path,[
            'folder' => $this->modelType
        ]);

        //$imageUrl = $result->getSecurePath();
        //$publicId = $result->getPublicId();
        //$this->saveImageUrl($this->modelType, $this->modelId, $imageUrl, $publicId);
    }

    /**
     * @throws \Exception
     */
    protected function saveImageUrl($modelType, $modelId, $imageUrl, $publicId): void
    {
        switch ($modelType) {
            case 'product':
                $product = \App\Models\Product::query()->findOrFail($modelId);
                if ($product) {
                    $product->productImages()->create([

                        'image_url' => $imageUrl,
                        'public_id' => $publicId,
                        'alt_text' => $product->name,
                    ]);
                }
                break;
            case 'category':
                $category = \App\Models\Category::query()->findOrFail($modelId);
                if ($category) {
                    $category->image_url = $imageUrl;
                    $category->public_id = $publicId;
                    $category->save();
                }
                break;
            default:
                throw new \Exception("Model type not supported");


        }
    }
}
