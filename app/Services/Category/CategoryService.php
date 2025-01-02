<?php

namespace App\Services\Category;



use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CategoryService implements CategoryServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;
    protected $cacheTag = 'categories';
    private array $relations = ['products', 'parents', 'children'];
    private array $columns = ['id', 'name', 'slug', 'parent_id', 'created_at', 'updated_at'];
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getAll()
    {
        $allQuery = http_build_query(request()->query());
        $key = 'all_products?' . $allQuery;
        return Cache::tags([$this->cacheTag])
            ->remember($key, 60, function () {
                $perPage = request()->query('per_page');
                $paginate = request()->query('paginate');
                if ($paginate) {
                    $categories = $this->loadRelationships($this->categoryRepository->query()->sortByColumn(columns: $this->columns))->paginate(is_numeric($perPage) ? $perPage : 15);
                    return [
                        'paginator' => $this->paginate($categories),
                        'data' => CategoryResource::collection(
                            $categories->items()
                        ),
                    ];
                } else {
                    $categories = $this->loadRelationships($this->categoryRepository->query()->sortByColumn(columns: $this->columns))->get();
                return [
                    'data' => CategoryResource::collection(
                        $categories
                    ),
                ];
                }
            });
    }
    public function mains()
    {
        $allQuery = http_build_query(request()->query());

        return Cache::tags($this->cacheTag)->remember('list/mains/category?' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');
            $categories = $this->loadRelationships($this->categoryRepository->query()->where('is_main', 1)->sortByColumn(columns: $this->columns))->paginate(is_numeric($perPage) ? $perPage : 15);
            return [
                'paginator' => $this->paginate($categories),
                'data' => CategoryResource::collection(
                    $categories->items()
                ),
            ];
        });
    }
    public function displayHomePage()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('display-home-products?' . $allQuery, 60, function () {
            $serial = request()->query('serial');
            $quantity = request()->query('quantity');
            $validator = Validator::make(['number' => $serial, 'quantity' => $quantity], [
                'number' => 'numeric',
                'quantity' => 'numeric'
            ]);
            if (!$serial) {
                $serial = 1;
            }
            if (!$quantity) {
                $quantity = 15;
            }
            if ($validator->failed()) {
                $serial = 1;
                $quantity = 15;
            }
            $listProduct = [];
            $category = $this->categoryRepository->query()->where('display', $serial)->first();
            $productsInCategory = $category->products()->where('status',true)->get();
            $listProduct = [...$productsInCategory];
            if (count($productsInCategory) < $quantity) {
                $listAllProducts = $this->productRepository->query()->where('status',true)->get();
                $arrayId = $category->products()->orderBy('qty_sold', 'desc')->get()->pluck('id');
                foreach ($listAllProducts as $p) {
                    if (!in_array($p->id, [...$arrayId])) {
                        $listProduct[] = $p;
                    }
                    if (count($listProduct) == $quantity) {
                        break;
                    }
                }
            }
           
            return [
                'category' => $category,
                'products' => ProductResource::collection($listProduct)
            ];
        });
    }
    public function findById(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('category/' . $id . '?' . $allQuery, 60, function () use ($id) {
            $category = $this->categoryRepository->find($id);
            if (!$category) {
                throw new ModelNotFoundException(__('messages.error-not-found'));
            }
            $category = $this->loadRelationships($category);
            return new CategoryResource($category);
        });
    }
    public function addProducts(int|string $id, array $products = [])
    {

        $category = $this->categoryRepository->find($id);
        if (!$category) throw new ModelNotFoundException(__('messages.error-not-found'));
        if ($products) $category->products()->syncWithoutDetaching($products);
        Cache::tags($this->cacheTag)->flush();
        return new CategoryResource($this->loadRelationships($category));
    }
    public function deleteProducts(int|string $id, array $products = [])
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) throw new ModelNotFoundException(__('messages.error-not-found'));
        if ($products) $category->products()->detach($products);
        Cache::tags($this->cacheTag)->flush();
        return new CategoryResource($this->loadRelationships($category));
    }
    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = [
        'parents' => []
    ])
    {
        $category = $this->categoryRepository->create($data);
        if (!$category) throw new \Exception(__('messages.error-not-found'));
        $listPar = [];
        if (count($option['parents']) > 0) {
            foreach ($option['parents'] as $parent) {
                $parCate = $this->categoryRepository->find($parent);
                if ($parCate && $parCate->is_main == 1) $listPar[] = $parCate->id;
            }
        }
        $category->parents()->attach($listPar);
        $category->slug = $this->slug($category->name, $category->id);
        $category->save();
        $category = $this->loadRelationships($category);
        Cache::tags($this->cacheTag)->flush();
        return new CategoryResource($category);
    }

    public function update(int|string $id, array $data, array $option = [
        'parents' => []
    ])
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) throw new ModelNotFoundException(__('messages.error-not-found'));
        if ($category->is_main == 1) return new CategoryResource($this->loadRelationships($category));
        $category->update($data);
        $listPar = [];
        if (count($option['parents']) > 0) {
            foreach ($option['parents'] as $parent) {
                $parCate = $this->categoryRepository->find($parent);
                if ($parCate && $parCate->is_main == 1) $listPar[] = $parCate->id;
            }
        }
        $category->parents()->sync($listPar);
        $category->slug = $this->slug($category->name, $category->id);
        $category->save();
        Cache::tags($this->cacheTag)->flush();
        return new CategoryResource($this->loadRelationships($category));
    }
    public function delete(int|string $id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }
        if ($category->is_main || $category->display) {
            throw new AuthorizationException(__('messages.delete-category-forbidden'));
        }
        $category->forceDelete($id);
        Cache::tags($this->cacheTag)->flush();
        return true;
    }
    public function forceDelete(int|string $id)
    {

        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }
        if ($category->is_main || $category->display) {
            throw new AuthorizationException('Forbidden');
        }

        $category->forceDelete($id);
        Cache::tags($this->cacheTag)->flush();
        return true;
    }
    protected function slug(string $name, int|string $id)
    {
        $slug = Str::slug($name) . '.' . $id;
        return $slug;
    }
}
