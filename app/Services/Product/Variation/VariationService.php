<?php

namespace App\Services\Product\Variation;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Resources\Product\VariationResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;


class VariationService implements VariationServiceInterface
{
    use CanLoadRelationships, Paginate, Cloudinary;

    private array $relations = ['product', 'images', 'values'];
    private array $columns = [
        'id',
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
        protected ProductRepositoryInterface   $productRepository
    )
    {
    }

    public function index(int|string $pid)
    {
        $product = $this->productRepository->query()->find($pid);
        if(!$product) throw new ModelNotFoundException('Product not found');
        if($product->variations){
            $attributes = [];
            foreach ($product->variations as $variation) {

                $variation->load(['images']);
                foreach ($variation->values as $value){
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
            'all_attribute' => AttributeResource::collection($product->ownAttributes()->orWhere('product_id',null)->get()->load('values'))
        ];
    }
    public function show(int|string $pid,int|string $id){
        $product = $this->productRepository->find($pid);
        if (!$product) throw new ModelNotFoundException('Product not found');
        $variation = $product->variations()->find($id);
        if (!$variation) throw new ModelNotFoundException('Variation not found');
        return new VariationResource($this->loadRelationships($variation));
    }
    public function create(int|string $pid, array $data, array $options = [
        'values' => [],
        'images' => []
    ])
    {
        if (empty($options['values'])) throw new \Exception('Could not find any attribute value');
        $product = $this->productRepository->find($pid);
        if (!$product) throw new ModelNotFoundException('Product not found');
        $data['qty_sold'] = 0;
        $variation = $product->variations()->create($data);

        if (!$variation) throw new \Exception('Failed to create variation');
        if (isset($options['images'])) $variation->images()->attach($options['images']);
        $variation->values()->attach($options['values']);
        $valuesName = [...$variation->values()->get()->pluck('value')];
        $strName = implode(' - ',$valuesName);
        $variation->name = $variation->product->name.'['.$strName.']';
        $variation->slug = $this->slug($variation->id);
        $variation->save();
        return new VariationResource($this->loadRelationships($variation));
    }

    public function createMany(int|string $pid, array $data)
    {
        $list = [];
        foreach ($data as $var) {
            if (empty($var['values']))
                $values = [];
            else $values = $var['values'];
            if (empty($var['images'])) $images = [];
            else $images = $var['images'];
            $variation = $this->create($pid, $var, [
                'values' => $values,
                'images' => $images
            ]);

            $list[] = $variation;
        }
        if (empty($list) || count($list) < 1) throw new \Exception('Can not create variations');
        return VariationResource::collection($list);
    }

    public function update(int|string $pid, int|string $id, array $data, array $options = [
        'values' => [],
        'images' => []
    ])
    {
        $product = $this->productRepository->find($pid);
        if (!$product) throw new ModelNotFoundException('Product not found');
        $variation = $this->repository->find($id);
        $variation->update($data);
        if (isset($options['images'])) $variation->images()->sync($options['images']);
        $variation->values()->sync($options['values']);
        $variation->slug = $this->slug($variation->id);
        $variation->save();
        return new VariationResource($this->loadRelationships($variation));
    }
    public function destroy(int|string $pid, int|string $id)
    {
        $product = $this->productRepository->find($pid);
        if (!$product) throw new ModelNotFoundException('Product not found');
        $variation = $this->repository->find($id);
        if (!$variation) throw new ModelNotFoundException('Variation not found');
        $variation->delete();
        return true;
    }
    protected function slug(int|string $id)
    {
        $variation = $this->repository->find($id);
        if (!$variation) throw new ModelNotFoundException('Variation not found');


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
