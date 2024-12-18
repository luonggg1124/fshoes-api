<?php

namespace App\Services\Product\Variation;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Resources\Product\VariationResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class VariationService implements VariationServiceInterface
{
    use CanLoadRelationships, Paginate, Cloudinary;
    protected $cacheTag = 'variations';
    protected $cacheTagProduct = 'products';
    private array $relations = ['product', 'images', 'values','statistics','products'];
    private array $columns = [
        'id',
        'slug',
        'price',
        'short_description',
        'description',
        'sku',
        'status',
        'qty_sold',
        'stock_qty',
    ];

    public function __construct(
        protected VariationRepositoryInterface $repository,
        protected ProductRepositoryInterface   $productRepository
    ) {}
    

    public function index(int|string $pid)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('all-variation/' . $pid . '?' . $allQuery, 60, function () use ($pid) {
            $product = $this->productRepository->query()->find($pid);
            if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
            if ($product->variations) {
                $attributes = [];
                foreach ($product->variations as $variation) {

                    $variation->load(['images']);
                    foreach ($variation->values as $value) {
                        $attributes[$value->attribute->id]['id'] = $value->attribute->id;
                        $attributes[$value->attribute->id]['name'] = $value->attribute->name;
                        $attributes[$value->attribute->id]['values'][] = [
                            'id' => $value->id,
                            'value' => $value->value,
                        ];
                        $attributes[$value->attribute->id]['values'] = collect($attributes[$value->attribute->id]['values'])->unique('id');
                        unset($value->attribute);
                    }
                }
                // automatically assign attributes
                $product->attributes = [...$attributes];
            }
            return [
                'variations' => VariationResource::collection($product->variations),
                'ownAttributes' => $product->attributes,
                'all_attribute' => AttributeResource::collection($product->ownAttributes()->orWhere('product_id', null)->get()->load('values'))
            ];
        });
    }
    public function show(int|string $pid, int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('product/' . $pid . '/' . $id . '?' . $allQuery, 60, function () use ($pid, $id) {
            $product = $this->productRepository->find($pid);
            if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
            $variation = $product->variations()->find($id);
            if (!$variation) throw new ModelNotFoundException(__('messages.error-not-found'));
            return new VariationResource($this->loadRelationships($variation));
        });
    }
    public function create(int|string $pid, array $data, array $options = [
        'values' => [],
        'images' => []
    ])
    {
        $variation = DB::transaction(function () use ($pid, $data, $options) {
            if (empty($options['values'])) throw new \Exception(__('messages.product.error-not-attribute'));
            $product = $this->productRepository->find($pid);
            if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
            $data['qty_sold'] = 0;
            $variation = $product->variations()->create($data);

            if (!$variation) throw new \Exception(__('messages.product.error-failed-create.variant'));
            if (isset($options['images'])) $variation->images()->attach($options['images']);
            $variation->values()->attach($options['values']);
            $valuesName = [...$variation->values()->get()->pluck('value')];
            $strName = implode(' - ', $valuesName);
            $variation->name = $variation->product->name . '[' . $strName . ']';
            $variation->slug = $this->slug($variation->id);
            $variation->classify = $strName;
            $variation->save();
            return $variation;
        });
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return new VariationResource($this->loadRelationships($variation));
    }

    public function createMany(int|string $pid, array $data)
    {
        $list = [];
      
        foreach ($data as $var) {
            if (empty($var['values'])){
                $values = [];
            }
            else {
                $values = $var['values'];
            }
            if (empty($var['images'])) {
                $images = [];
            }
            else {
                $images = $var['images'];
            }
            
            $variation = $this->create($pid, $var, [
                'values' => $values,
                'images' => $images
            ]);
           
            $list[] = $variation;
        }
       
        if (empty($list) || count($list) < 1) throw new \Exception(__('messages.product.error-create.variant'));
        Cache::tags([$this->cacheTag])->flush();
        return VariationResource::collection($list);
    }

    public function update(int|string $pid, int|string $id, array $data, array $options = [
        'values' => [],
        'images' => []
    ])
    {
        
        $variation = DB::transaction(function () use ($pid, $id, $data, $options) {
            $product = $this->productRepository->find($pid);
            if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
            $variation = $this->repository->find($id);
            if(!$variation) throw new ModelNotFoundException(__('messages.error-not-found'));
            $variation->update($data);
            
            if (isset($options['images'])) $variation->images()->sync($options['images']);
            $variation->values()->sync($options['values']);
            $variation->slug = $this->slug($variation->id);
            $variation->save();
            return $variation;
        });
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return new VariationResource($this->loadRelationships($variation));
    }
    public function destroy(int|string $pid, int|string $id)
    {
        $product = $this->productRepository->find($pid);
        if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
        $variation = $this->repository->find($id);
        if (!$variation) throw new ModelNotFoundException(__('messages.error-not-found'));
        $variation->delete();
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return true;
    }
    protected function slug(int|string $id)
    {
        $variation = $this->repository->find($id);
        if (!$variation) throw new ModelNotFoundException(__('messages.error-not-found'));


        $values = $variation->values()->pluck('value');
        $valueArr = [];
        foreach ($values as $value) {
            $v = Str::slug($value);
            $valueArr[] = $v;
        }
        $valueStr = implode('-', $valueArr);
        $slug = $valueStr . '.' . $id;
        $exists = $this->repository->query()->where('slug', $slug)->exists();
        if ($exists) {
            return Str::random(2) . '.' . $valueStr . '.' . $id;
        }
        return $slug;
    }
}
