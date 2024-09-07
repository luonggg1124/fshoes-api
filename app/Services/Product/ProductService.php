<?php

namespace App\Services\Product;

use App\Http\Traits\Cloudinary;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService implements ProductServiceInterface
{
    use CanLoadRelationships,Cloudinary,Paginate;

    private array $relations = ['categories','productImages','variations'];
    public function __construct(protected ProductRepositoryInterface $productRepository){}

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
    public function findById(int|string $id){
        $product = $this->productRepository->find($id);

        if(!$product){
            throw new ModelNotFoundException('Category not found');
        }
        $product = $this->loadRelationships($product);
        return new ProductResource($product);
    }
    public function create(array $data, array $option = [])
    {
        
    }
}
