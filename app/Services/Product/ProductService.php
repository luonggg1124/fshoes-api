<?php

namespace App\Services\Product;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Resources\Product\ProductDetailResource;
use App\Http\Resources\Product\ProductSummary;
use App\Http\Traits\Paginate;
use App\Http\Traits\Cloudinary;

use App\Repositories\Product\Variation\VariationRepositoryInterface;
use App\Services\Image\ImageServiceInterface;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery\Exception;

class ProductService implements ProductServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;
    protected $cacheTag = 'products';
    private array $relations = ['categories', 'images', 'variations', 'ownAttributes', 'statistics'];
    private array $columns = [
        'id',
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
        protected ProductRepositoryInterface   $productRepository,
        protected VariationRepositoryInterface $variationRepository,
        protected ImageServiceInterface        $imageService,
        protected CategoryRepositoryInterface $categoryRepository

    ) {}

    public function all()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('all/products?' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');
            $searchKey = request()->query('search');
            $paginate = request()->query('paginate');
            if ($paginate) {
                $products = $this->loadRelationships($this->productRepository->query()->when($searchKey, function ($q) use ($searchKey) {
                    $q->where('name', 'like', '%' . $searchKey . '%');
                })->sortByColumn(columns: $this->columns))->paginate(is_numeric($perPage) ? $perPage : 15);
                return [
                    'paginator' => $this->paginate($products),
                    'data' => ProductResource::collection(
                        $products->items()
                    ),
                ];
            } else {
                $products = $this->loadRelationships($this->productRepository->query()->when($searchKey, function ($q) use ($searchKey) {
                    $q->where('name', 'like', '%' . $searchKey . '%');
                })->sortByColumn(columns: $this->columns))->get();
                return [
                    'data' => ProductResource::collection($products),
                ];
            }
        });
    }

    public function productByCategory(int|string $categoryId)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('productByCategory/' . $categoryId . '?' . $allQuery, 60, function () use ($categoryId) {
            $products = $this->productRepository->query()->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })->with(['categories'])->get();
            return ProductResource::collection($products);
        });
    }



    public function findById(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('category/' . $id . '?' . $allQuery, 60, function () use ($id) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                throw new ModelNotFoundException(__('messages.error-not-found'));
            }
            $product = $this->loadRelationships($product);
            return new ProductResource($product);
        });
    }

    public function productDetail(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('product-detail/' . $id . '?' . $allQuery, 60, function () use ($id) {
            $product = $this->productRepository->query()->find($id);
            
            if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
            if ($product->variations) {
                $attributes = [];
                foreach ($product->variations as $variation) {
                    $variation->load('images');
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
                $product->attributes = [...$attributes];
            }
            $productRelated = [];
            if ($product->categories) {
                foreach ($product->categories as $category) {
                    foreach ($category->products()->orderBy('qty_sold', 'desc')->take(3)->get() as $p)
                        $productRelated[] = $p;
                    if (count($productRelated) === 20) break;
                }
            }
            $uniProductRelated = collect($productRelated)->unique('id');
            $collectProduct = [];
            if (count($uniProductRelated) < 20) {
                $topSold = $this->productRepository->query()->orderBy('qty_sold', 'desc')->take(30)->get();
                foreach ($topSold as $item) {
                    $uniProductRelated[] = $item;
                    if (count(collect($uniProductRelated)->unique('id')) === 20) {
                        $collectProduct = collect($uniProductRelated)->unique('id');
                        break;
                    }
                }
            } else {
                $collectProduct = $uniProductRelated;
            }
            $suggestedProduct = [...$collectProduct];
            foreach ($collectProduct as $item) $item->load('categories');
            $product->suggestedProduct = $suggestedProduct;
            return new ProductDetailResource($product);
        });
    }

    public function create(
        array $data,
        array $options = [
            'images' => [],
            'categories' => []
        ]
    ) {
        return DB::transaction(function () use ($data, $options) {
            if (empty($data['status'])) $data['status'] = 0;
            $product = $this->productRepository->create($data);
            if($product->status == 0){
                $product->created_at = now();
            }
            if (!$product) throw new \Exception(__('messages.error-not-found'));
            $product->slug = $this->slug($product->name, $product->id);
            $product->save();
            if (count($options['images']) > 0) {
                $product->images()->attach($options['images']);
            }
            if (count($options['categories']) > 0) $product->categories()->attach($options['categories']);
            Cache::tags([$this->cacheTag, ...$this->relations])->flush();
            return new ProductResource($this->loadRelationships($product));
        });
    }

    public function update(int|string $id, array $data, array $options = [
        'images' => [],
        'categories' => []
    ])
    {
        return DB::transaction(function () use ($id, $data, $options) {
            $product = $this->productRepository->find($id);

            if (!$product) throw new \Exception(__('messages.error-not-found'));
            $product->update($data);
            $product->slug = $this->slug($product->name, $product->id);
            $product->save();
            if (count($options['images']) > 0) {
                $product->images()->sync($options['images']);
            }
            if (count($options['categories']) > 0) $product->categories()->sync($options['categories']);
            Cache::tags([$this->cacheTag, ...$this->relations])->flush();
            return new ProductResource($this->loadRelationships($product));
        });
    }

    public function productAttribute(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('product-attribute' . $id . '?' . $allQuery, 60, function () use ($id) {
            $product = $this->productRepository->find($id);
            if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
            $attributes = $product->ownAttributes()->orWhere('product_id', null)->get();
            return AttributeResource::collection($attributes->load(['values']));
        });
    }

    public function createAttributeValues(int $id, string|int $attributeName, array $values = [])
    {
        $product = $this->productRepository->find($id);
        if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
        if (!$attributeName) throw new Exception(__('messages.error-internal-server'));
        $attribute = $product->ownAttributes()->create([
            'name' => $attributeName,
        ]);

        foreach ($values as $value) {
            $attribute->values()->create(['value' => $value]);
        }
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return [
            new AttributeResource($attribute->load(['values'])),
        ];
    }

    public function updateStatus(string|int|bool $status, int|string $id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));
        $product->status = $status;
        $product->save();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return new ProductResource($this->loadRelationships($product));
    }

    protected function slug(string $name, int|string $id)
    {
        $slug = Str::slug($name) . '.' . $id;
        return $slug;
    }

    public function destroy(int|string $id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) throw new ModelNotFoundException(__('messages.error-not-found'));

        $product->delete();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return true;
    }

    public function productWithTrashed()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('product-with-trashed' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');
            $products = $this->loadRelationships($this->productRepository->query()->withTrashed()->sortByColumn(columns: $this->columns))->paginate($perPage);
            return [
                'paginator' => $this->paginate($products),
                'data' => ProductResource::collection(
                    $products->items()
                ),
            ];
        });
    }

    public function productTrashed()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('product-trashed' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');

            $products = $this->loadRelationships($this->productRepository->query()->onlyTrashed()->sortByColumn(columns: $this->columns, defaultColumn: 'deleted_at'))->paginate($perPage);
            return [
                'paginator' => $this->paginate($products),
                'data' => ProductResource::collection(
                    $products->items()
                ),
            ];
        });
    }

    public function restore(int|string $id)
    {
        $product = $this->productRepository->query()->withTrashed()->find($id);
        if (!$product) {
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }
        $product->restore();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return new ProductResource($this->loadRelationships($product));
    }

    public function findProductTrashed(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('find-product-trashed' . $id . '?' . $allQuery, 60, function () use ($id) {
            $product = $this->productRepository->query()->withTrashed()->find($id);
            if (!$product) {
                throw new ModelNotFoundException(__('messages.error-not-found'));
            }
            $product = $this->loadRelationships($product);
            return new ProductResource($product);
        });
    }

    public function forceDestroy(int|string $id)
    {
        $product = $this->productRepository->query()->withTrashed()->find($id);
        if (!$product) {
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }
        $product->forceDelete();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return true;
    }

    public function findByAttributeValues()
    {
        $allQuery = http_build_query(request()->query());

        return Cache::tags([$this->cacheTag])->remember('find-by-attribute-values?' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');
            $attributeQuery = request()->query('attributes');
            $categoryId = request()->query('categoryId');
            $search = request()->query('search');

            $arrAttrVal = [];
            if (empty($categoryId)) {
                $categoryId = '';
            }
            $category = $this->categoryRepository->find($categoryId);

            if ($attributeQuery) {
                $intElements = array_filter(explode('-', $attributeQuery), function ($value) {
                    return ctype_digit($value);
                });
                $intElements = array_map('intval', $intElements);
                $arrAttrVal = $intElements;
            }

            $products = $this->productRepository->query()->when(count($arrAttrVal) > 0, function ($q) use ($arrAttrVal) {
                $q->whereHas('variations', function ($q) use ($arrAttrVal) {
                    $q->whereHas('values', function ($q) use ($arrAttrVal) {
                        $q->whereIn('attribute_value_id', $arrAttrVal);
                    });
                });
            })->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->when($category, function ($q) use ($categoryId) {
                    $q->whereHas('categories', function ($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                })->with(['categories'])->sortByColumn(columns: $this->columns)->paginate($perPage);
            return [
                'paginator' => $this->paginate($products),
                'data' => ProductResource::collection(
                    $products->items()
                ),
                'category' =>  $category
            ];
        });
    }
    public function allSummary()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('all-summary-products?' . $allQuery, 60, function () {
            $products = $this->loadRelationships($this->productRepository->query()->orderBy('updated_at', 'desc'))->get();
            return ProductSummary::collection($products);
        });
    }
    //Just for statistics
    public function countByDateForStatistics($from, $to): int
    {
        $count = $this->productRepository->query()->whereBetween('created_at', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay()
        ])->count();
        return $count;
    }
}
