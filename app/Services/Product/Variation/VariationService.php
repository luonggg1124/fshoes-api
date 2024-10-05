<?php

namespace App\Services\Product\Variation;

use App\Http\Resources\Product\VariationResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;


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
    public function create(int|string $pid,array $data,array $options = [
        'values' => [],
        'images' => []
    ]){
        if(empty($options['values'])) throw new \Exception('Could not find any attribute value');
        $product = $this->productRepository->find($pid);
        if(!$product) throw new ModelNotFoundException('Product not found');
        $data['qty_sold'] = 0;
        $variation = $product->variations()->create($data);

        if(!$variation) throw new \Exception('Failed to create variation');
        if(isset($options['images'])) $variation->images()->attach($options['images']);
        $variation->values()->attach($options['values']);
        $variation->slug = $this->slug($variation->id);
        $variation->save();
        return new VariationResource($this->loadRelationships($variation));
    }
    public function createMany(int|string $pid,array $data){
        $list = [];
        foreach ($data as $var){
            if(empty($var['values']))
                $values = [];
            else $values = $var['values'];
            if (empty($var['images'])) $images = [];
            else $images = $var['images'];
            $variation = $this->create($pid,$var,[
                'values' => $values,
                'images' => $images
            ]);
            $list[] = $variation;
        }
        if(empty($list) || count($list) < 1) throw new \Exception('Can not create variations');
        return VariationResource::collection($list);
    }

    protected function slug(int|string $id){
        $variation = $this->repository->find($id);
        if(!$variation) throw new ModelNotFoundException('Variation not found');

        $name = $variation->product->name;
        if(!$name) throw new \Exception('The product name is not found');
        $values = $variation->values()->pluck('value');
        $valueArr = [];
        foreach ($values as $value){
            $v = Str::slug($value);
            $valueArr[] = $v;
        }
        $valueStr = implode('-', $valueArr);
        $slug = Str::slug($name).'-'.$valueStr.'.'.$id;
        $exists = $this->repository->query()->where('slug',$slug)->exists();
        if($exists){
            return Str::slug($name).'-'.Str::random(2).'.'.$valueStr.'.'.$id;
        }
        return $slug;
    }
}
