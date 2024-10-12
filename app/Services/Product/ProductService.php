<?php

namespace App\Services\Product;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Resources\Product\ProductDetailResource;
use App\Http\Traits\Paginate;
use App\Http\Traits\Cloudinary;

use App\Services\Image\ImageServiceInterface;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Repositories\Product\ProductRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery\Exception;

class ProductService implements ProductServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;

    private array $relations = ['categories', 'images', 'variations','ownAttributes'];
    private array $columns = [
        'name',
        'slug',
        'price',
        'short_description',
        'description',
        'sku',
        'status',
        'created_at',
        'updated_at',
    ];
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ImageServiceInterface $imageService,
    ) {
    }

    public function all()
    {
        $perPage = request()->query('per_page');
        $products = $this->loadRelationships($this->productRepository->query()->orderBy('updated_at','desc'))->paginate($perPage);
        return [
            'paginator' => $this->paginate($products),
            'data' => ProductResource::collection(
                $products->items()
            ),
        ];


    }
    public function thisWeekProducts(){
        $products = $this->productRepository->query()->with(['categories'])
            ->whereHas('categories',function ($query){
                $query->where('categories.name','Trend This Week');
            });

        return ProductResource::collection($this->loadRelationships($products)->take(15)->get());
    }
    public function shopBySports()
    {
        $products = $this->productRepository->query()->with(['categories'])
            ->whereHas('categories',function ($query){
                $query->where('categories.name','like','Shop By Sport');
            });
        return ProductResource::collection($this->loadRelationships($products)->get());
    }
    public function bestSellingProducts(){
        $products = $this->productRepository->query()->with(['categories'])
            ->whereHas('categories',function ($query){
                $query->where('categories.name','Best Selling');
            });
        return ProductResource::collection($this->loadRelationships($products)->take(5)->get());
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
    public function productDetail(int|string $id){
        $product = $this->productRepository->query()->find($id);
        if(!$product) throw new ModelNotFoundException('Product not found');
        if($product->variations){
            $attributes = [];
            foreach ($product->variations as $variation) {
                foreach ($variation->values as $value){
                    $attributes[$value->attribute->id][$value->id] = $value->value;
                }
            }
            // automatically assign attributes
            $product->attributes = $attributes;
        }
        $productRelated = [];
        if($product->categories){
            foreach ($product->categories as $category){
                foreach ($category->products()->orderBy('qty_sold','desc')->take(3)->get() as $p)
                $productRelated[] = $p;
                if(count($productRelated) === 20)break;
            }

        }
        $uniProductRelated = collect($productRelated)->unique('id');
        $collectProduct = [];
        if(count($uniProductRelated) < 20){
            $topSold = $this->productRepository->query()->orderBy('qty_sold','desc')->take(30)->get();
            foreach ($topSold as $item){
                $uniProductRelated[] = $item;
                if(count(collect($uniProductRelated)->unique('id')) === 20) {
                    $collectProduct = collect($uniProductRelated)->unique('id');
                    break;
                }
            }
        }else{
            $collectProduct = $uniProductRelated;
        }
        $suggestedProduct = [...$collectProduct];
        $product->suggestedProduct = $suggestedProduct;
        return new ProductDetailResource($product);

    }

    public function create(
        array $data,
        array $options = [
            'images' => [],
            'categories' => []
        ]
    )
    {

        return DB::transaction(function () use ($data, $options) {
            $product = $this->productRepository->create($data);
            if(!$product) throw new \Exception('Cannot create product');
            $product->slug = $this->slug($product->name,$product->id);
            $product->save();
            if(count($options['images']) > 0){
                $product->images()->attach($options['images']);
            }
            if(count($options['categories']) > 0) $product->categories()->attach($options['categories']);
            return new ProductResource($this->loadRelationships($product));
        });
    }
    public function update(int|string $id, array $data,array $options=[
        'images' => [],
        'categories' => []
    ])
    {
        return DB::transaction(function () use ($id,$data, $options) {
            $product = $this->productRepository->find($id);

            if(!$product) throw new \Exception('Product not found');
            $product->update($data);
            $product->slug = $this->slug($product->name,$product->id);
            $product->save();
            if(count($options['images']) > 0){
               $product->images()->sync($options['images']);
            }
            if(count($options['categories']) > 0) $product->categories()->sync($options['categories']);
            return new ProductResource($this->loadRelationships($product));
        });
    }
    public function productAttribute(int|string $id){
        $product = $this->productRepository->find($id);
        if(!$product) throw new ModelNotFoundException('Product not found');
        $attributes = $product->ownAttributes()->orWhere('product_id',null)->get();
        return AttributeResource::collection($attributes->load(['values']));
    }
    public function createAttributeValues(int $id,string|int $attributeName,array $values = []){
        $product = $this->productRepository->find($id);
        if(!$product) throw new ModelNotFoundException('Product not found');
        if(!$attributeName) throw new Exception('Attribute name not set');
        $attribute = $product->ownAttributes()->create([
            'name' => $attributeName,
        ]);

        foreach ($values as $value){
            $attribute->values()->create(['value' => $value]);
        }
        return [
            new AttributeResource($attribute->load(['values'])),
        ];
    }
    public function updateStatus(string|int|bool $status,int|string $id){
        $product = $this->productRepository->find($id);
        if(!$product) throw new ModelNotFoundException('Product not found');
        $product->status = $status;
        $product->save();
        return new ProductResource($this->loadRelationships($product));
    }
    protected function slug(string $name, int|string $id){
        $slug = Str::slug($name).'.'.$id;
        $exists = $this->productRepository->query()->where('slug',$slug)->exists();
        if($exists){
            return Str::slug($name).'-'.Str::random(2).'.'.$id;
        }
        return $slug;
    }
    public function destroy(int|string $id){
        $product = $this->productRepository->find($id);
        if(!$product) throw new ModelNotFoundException('Product not found');
        $product->variations()->delete();
        $product->delete();
        return true;
    }
    public function productWithTrashed()
    {
        $perPage = request()->query('per_page');
        $products = $this->loadRelationships($this->productRepository->query()->withTrashed()->latest())->paginate($perPage);
        return [
            'paginator' => $this->paginate($products),
            'data' => ProductResource::collection(
                $products->items()
            ),
        ];
    }
    public function productTrashed(){
        $perPage = request()->query('per_page');

        $products = $this->loadRelationships($this->productRepository->query()->onlyTrashed()->orderBy('deleted_at','desc'))->paginate($perPage);
        return [
            'paginator' => $this->paginate($products),
            'data' => ProductResource::collection(
                $products->items()
            ),
        ];
    }
    public function restore(int|string $id)
    {
        $product = $this->productRepository->query()->withTrashed()->find($id);
        if (!$product) {
            throw new ModelNotFoundException('Product not found');
        }
        $product->restore();
        return new ProductResource($this->loadRelationships($product));
    }
    public function findProductTrashed(int|string $id){
        $product = $this->productRepository->query()->withTrashed()->find($id);
        if (!$product) {
            throw new ModelNotFoundException('Product not found');
        }
        $product = $this->loadRelationships($product);
        return new ProductResource($product);
    }
    public function forceDestroy(int|string $id){
        $product = $this->productRepository->query()->withTrashed()->find($id);
        if (!$product) {
            throw new ModelNotFoundException('Product not found');
        }
        $product->forceDelete();
        return true;
    }
}



