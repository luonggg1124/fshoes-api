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
use App\Repositories\Attribute\Value\AttributeValueRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
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
        'is_variant',
        'created_at',
        'updated_at',
    ];

    public function __construct(
        protected ProductRepositoryInterface   $productRepository,
        protected VariationRepositoryInterface $variationRepository,
        protected ImageServiceInterface        $imageService,
        protected CategoryRepositoryInterface $categoryRepository,
        protected AttributeValueRepositoryInterface $attributeValueRepository

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
            if (!$product->status) {
                throw new ModelNotFoundException(__('messages.error-not-found'));
            }
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
                        if ($p->id != $product->id) {
                            $productRelated[] = $p;
                        }
                    if (count($productRelated) === 20) break;
                }
            }
            $uniProductRelated = collect($productRelated)->unique('id');
            $collectProduct = [];
            if (count($uniProductRelated) < 20) {
                $topSold = $this->productRepository->query()->where('id', '!=', $product)->orderBy('qty_sold', 'desc')->take(30)->get();
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
            'categories' => [],
            'variants' => [],
        ]
    ) {


        if (isset($data['is_variant'])) {
            $createData = [
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'stock_qty' => 0,
                'image_url' => $data['image_url'],
                'qty_sold' => 0,
                'is_variant' => 1,
            ];
            
            return DB::transaction(function () use ($options, $createData) {
                $errors = [];
                $product = $this->productRepository->create($createData);
                if (!$product) throw new \Exception(__('không thể tạo sản phẩm'));
                $product->slug = $this->slug($product->name, $product->id);
                
                if (count($options['images']) > 0) {
                    $product->images()->attach($options['images']);
                }
                if (count($options['categories']) > 0) $product->categories()->attach($options['categories']);
                if (isset($options['variants']) && count($options['variants']) > 0) {
                    foreach ($options['variants'] as $variant) {
                        $variantData = [
                            'product_id' => $product->id,
                            'price' => $variant['price'] ?: 0,
                            'stock_qty' => $variant['stock_qty'] ?: 0,
                            'qty_sold' => 0,
                            'sku' => $variant['sku'] ?: null
                        ];
                        $existsValues = $this->attributeValueRepository->query()->whereIn('id', $variant['values'])->pluck('id')->toArray();
                        if (count([...$existsValues]) != count($variant['values']) || empty($variant['values'])) {
                            $errors['variant_value']= "Một số biến thể sẽ không được khởi tạo do thuộc tính không tồn tại";
                        } else {
                            sort($variant['values']);
                            $variantData['code_identifier'] = $product->id . implode('', $variant['values']);
                            $variation = $this->variationRepository->create($variantData);
                            if (!$variation) throw new \Exception(__('Có lỗi xảy ra!Hãy thử tạo lại sản phẩm'));
                            $variation->values()->attach($variant['values']);
                            $valuesName = [...$variation->values()->get()->pluck('value')];
                            $strName = implode(' - ', $valuesName);
                            $variation->name = $variation->product->name . '[' . $strName . ']';
                            $values = $variation->values()->pluck('value');
                            $valueArr = [];
                            foreach ($values as $value) {
                                $v = Str::slug($value);
                                $valueArr[] = $v;
                            }
                            $valueStr = implode('-', $valueArr);
                            $slug = $valueStr . '.' . $variation->id;
                            $variation->slug = $slug;
                            $variation->classify = $strName;
                            $variation->save();
                        }
                    }
                }
                $product->stock_qty = $product->variations()->pluck('stock_qty')->toArray() ? array_sum($product->variations()->pluck('stock_qty')->toArray()) : 0;
                $product->save();
                Cache::tags([$this->cacheTag, ...$this->relations])->flush();

                return [
                    'errors' => $errors,
                    'products' =>  new ProductResource($this->loadRelationships($product)),
                ];
            });
        } else {
            $createData = [
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'stock_qty' => $data['stock_qty'],
                'image_url' => $data['image_url'],
                'qty_sold' => 0,
                'is_variant' => null
            ];
            
            return  DB::transaction(function () use ($options, $createData) {
                $product = $this->productRepository->create($createData);
                if (!$product) throw new \Exception(__('không thể tạo sản phẩm'));
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

    }




    public function update(int|string $id, array $data, array $options = [
        'images' => [],
        'categories' => [],
        'variants' => [],
    ])
    {
        $product = $this->productRepository->find($id);
        if (!$product) throw new ModelNotFoundException('Không tìm thấy sản phẩm');
        if ($product->is_variant) {
            return DB::transaction(function () use ($options, $data, $product) {
                $product->variations()->delete();
                $errors = [];
                
                $product->name = $data['name'];
                $product->slug = $this->slug($product->name, $product->id);
                if(isset($data['description'])){
                    $product->description = $data['description'];
                }
                if(isset($data['short_description'])){
                    $product->short_description = $data['short_description'];
                }
                
                $product->image_url = $data['image_url'];
                $product->save();

                if (isset($options['images']) && count($options['images']) > 0) {
                    $product->images()->sync($options['images']);
                }
                if (count($options['categories']) > 0) $product->categories()->sync($options['categories']);
                if (isset($options['variants']) && count($options['variants']) > 0) {
                    foreach ($options['variants'] as $variant) {
                        if (empty($variant['values'])) $errors['empty_variant_value'] = 'Một số biến thể không thể tìm thấy thuộc tính nên sẽ không được khởi tạo';
                        sort($variant['values']);
                        $code_identifier = $product->id . implode('', $variant['values']);
                        $variation = $product->variations()->withTrashed()->where('code_identifier', $code_identifier)->first();
                        if ($variation) {
                            $variation->price = $variant['price'] ?? $variation->price;
                            $variation->stock_qty = $variant['stock_qty'] ?? $variation->stock_qty;
                            $variation->sku = $variant['sku'] ?? $variation->sku;
                            $variation->name = $variation->product->name . " " . $variation->classify;
                            $variation->deleted_at = null;
                            $variation->save();
                            if (isset($variant['images']) && count($variant['images']) > 0) {
                                $variation->images()->sync($variant['images']);
                            }
                        } else {
                            sort($variant['values']);
                            $code_identifier = $product->id . implode('', $variant['values']);
                            $variantData = [
                                'product_id' => $product->id,
                                'price' => $variant['price'] ?: 0,
                                'stock_qty' => $variant['stock_qty'] ?: 0,
                                'qty_sold' => 0,
                                'sku' => $variant['sku'] ?: null,
                                'code_identifier' => $code_identifier
                            ];
                            $existsValues = $this->attributeValueRepository->query()->whereIn('id', $variant['values'])->pluck('id')->toArray();
                            if (count([...$existsValues]) != count($variant['values']) || empty($variant['values'])) {
                                $errors['variant_value'] = "Một số biến thể sẽ không được khởi tạo do thuộc tính không tồn tại";
                            } else {
                                $variation = $this->variationRepository->create($variantData);
                                if (isset($variant['images']) && count($variant['images']) > 0) {
                                    $variation->images()->attach($variant['images']);
                                }
                                $variation->values()->attach($variant['values']);
                                $valuesName = [...$variation->values()->get()->pluck('value')];
                                $strName = implode(' - ', $valuesName);
                                $variation->name = $variation->product->name . '[' . $strName . ']';
                                $values = $variation->values()->pluck('value');
                                $valueArr = [];
                                foreach ($values as $value) {
                                    $v = Str::slug($value);
                                    $valueArr[] = $v;
                                }
                                $valueStr = implode('-', $valueArr);
                                $slug = $valueStr . '.' . $variation->id;
                                $variation->slug = $slug;
                                $variation->classify = $strName;
                                $variation->save();
                            }
                        }
                    }
                }
                Cache::tags([$this->cacheTag, ...$this->relations])->flush();
                return [
                    'product' =>  new ProductResource($this->loadRelationships($product)),
                    'errors' => $errors
                ];
            });
        } else {

            $product = $this->productRepository->find($id);
            if (!$product) throw new ModelNotFoundException('Không tìm thấy sản phẩm');
            $update =  DB::transaction(function () use ($options, $product, $data) {
                $product->name = $data['name'];
                $product->price = $data['price'];
                if(isset($data['description'])){
                    $product->description = $data['description'];
                }
                if(isset($data['short_description'])){
                    $product->short_description = $data['short_description'];
                }
                if(isset($data['stock_qty'])){
                    $product->stock_qty = $data['stock_qty'];
                }
                
                $product->image_url = $data['image_url'];
                $product->slug = $this->slug($product->name, $product->id);
                $product->save();
                if (count($options['images']) > 0) {
                    $product->images()->sync($options['images']);
                }
                if (count($options['categories']) > 0) $product->categories()->sync($options['categories']);
                Cache::tags([$this->cacheTag, ...$this->relations])->flush();
                return new ProductResource($this->loadRelationships($product));
            });
            if($update){
                return $update;
            }else{
                throw new Exception('Không thể cập nhật sản phẩm');
            }
        }
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

    public function filterProduct()
    {
        $allQuery = http_build_query(request()->query());

        return Cache::tags([$this->cacheTag])->remember('product/filter?' . $allQuery, 60, function () {
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

            $products = $this->productRepository->query()->where('status', 1)->when(count($arrAttrVal) > 0, function ($q) use ($arrAttrVal) {
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
}
