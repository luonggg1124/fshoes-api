<?php

namespace App\Services\Product\Variation;

use App\Http\Resources\Product\VariationResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;


class VariationService implements VariationServiceInterface
{
    use CanLoadRelationships,Paginate,Cloudinary;
    private array $relations = ['product','images','values'];
    private array $columns = [
        'slug',
        'price',
        'short_description',
        'description',
        'sku',
        'status',
        'qty_sold',
        'stock_qty',];
    public function __construct(
        protected VariationRepositoryInterface $repository,
        protected ProductRepositoryInterface $productRepository
    )
    {
    }
    public function index(int|string $pid){
        $product = $this->productRepository->find($pid);

        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $variations = $product->variations()->orderBy($column, $sort);
        return VariationResource::collection($this->loadRelationships($variations)->get());
    }
    public function create(int|string $pid,array $data,){

    }
}
