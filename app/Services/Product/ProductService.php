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
use Illuminate\Support\Facades\DB;

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
        $perPage = request()->query('per_page');
        $column = request()->query('column') ?? 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $products = $this->loadRelationships($this->productRepository->query()->orderBy($column,$sort)->latest())->paginate($perPage);
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
            throw new ModelNotFoundException('Product not found');
        }
        $product = $this->loadRelationships($product);
        return new ProductResource($product);
    }

    public function create(
        array $data,
        array $options = [
            'images' => []
        ]
    )
    {
        return DB::transaction(function () use ($data, $options) {
            $product = $this->productRepository->create($data);
            if(!$product) throw new \Exception('Product not created');
            if(count($options['images']) > 0){
                $this->createProductImages($options['images'],$product);
            }
            return $this->loadRelationships($product);
        });
    }
    public function createVariation(
        array $data = [],
        array $options = [
            'images' => []
        ]
    )
    {
        $variation = $this->productRepository->createVariations($data);

        return $variation;
    }
    public function updateVariation(int|string $id, array $data = [], array $options = [])
    {

    }
    protected function createProductImages(array $data ,Product $model)
    {
        if ($data && count($data) > 0) {
            foreach ($data as $image) {
                if ($image instanceof UploadedFile) {
                    $upload = $this->uploadImageCloudinary($image);
                    $model->id;
                    $img = [
                        'product_id' => $model->id,
                        'image_url' => $upload['path'],
                        'public_id' => $upload['public_id'],
                        'alt_text' => 'image '.$model->name ?? '',
                    ];
                    $this->productRepository->createImage($img);
                }
            }
            return $model->productImages();
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
