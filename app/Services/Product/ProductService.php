<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Http\Traits\Paginate;
use App\Http\Traits\Cloudinary;

use Illuminate\Http\UploadedFile;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\ProductVariations;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService implements ProductServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;

    private array $relations = ['categories', 'productImages', 'variations'];
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
    ) {
    }

    public function all()
    {
        $products = $this->loadRelationships($this->productRepository->query()->latest())->paginate();
        return [
            'paginator' => $this->paginate($products),
            'data' => ProductResource::collection(
                $products->items()
            ),
        ];


    }
    public function findById(int|string $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new ModelNotFoundException('Category not found');
        }
        $product = $this->loadRelationships($product);
        return new ProductResource($product);
    }
    public function create(array $data, array $option = [])
    {

    }
    public function createVariation(array $data = [], array $option = [])
    {
        $variation = $this->productRepository->createVariations($data);
        if ($option['images']) {
            $this->createImages($option['images'],$variation);
        }
        return $variation;
    }
    public function updateVariation(int|string $id, array $data = [], array $option = [])
    {

    }
    protected function createImages(array $data ,Product|ProductVariations $model)
    {
        if ($data && count($data) > 0) {
            foreach ($data as $image) {
                if ($image instanceof UploadedFile) {
                    if ($model instanceof ProductVariations) {
                        $upload = $this->uploadImageCloudinary($image);
                        $upload['product_variation_id'] = $model->id;
                        $upload['product_id'] = $model->product_id;
                        $this->productRepository->createImage($upload);
                    }else{
                        $upload = $this->uploadImageCloudinary($image);
                        $upload['product_id'] = $model->id;
                        $this->productRepository->createImage($upload);
                    }
                }
            }
            return $model;

        }
        return null;
    }
    // protected function updateImage(array $data ,Product|ProductVariations $model)
    // {
    //     if ($data && count($data) > 0) {
    //         foreach ($data as $image) {
    //             if ($image instanceof UploadedFile) {
    //                 if ($model instanceof ProductVariations) {
    //                     $upload = $this->uploadImageCloudinary($image);
    //                     $upload['product_variation_id'] = $model->id;
    //                     $upload['product_id'] = $model->product_id;
    //                     $this->productRepository->createImage($upload);
    //                 }else{
    //                     $upload = $this->uploadImageCloudinary($image);
    //                     $upload['product_id'] = $model->id;
    //                     $this->productRepository->createImage($upload);
    //                 }
    //             }
    //         }
    //         return $model;

    //     }
    //     return null;
    // }

}
