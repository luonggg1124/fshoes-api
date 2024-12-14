<?php

namespace App\Services\Sale;

use App\Http\Resources\Sale\SaleResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use App\Repositories\Sale\SaleRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SaleService implements SaleServiceInterface
{
    use CanLoadRelationships, Paginate;
    protected $cacheTag = 'sales';
    
    private array $relations = ['products', 'variations','statistics'];
    private array $columns = ['id', 'name', 'type', 'start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'];
    public function __construct(
        protected SaleRepositoryInterface $repository,
        protected ProductRepositoryInterface $productRepository,
        protected VariationRepositoryInterface $variationRepository
    ) {}

    public function all()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('reviews-all' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');

            $sales = $this->loadRelationships($this->repository->query()->sortByColumn(columns: $this->columns))->paginate($perPage);
            return [
                'paginator' => $this->paginate($sales),
                'data' => SaleResource::collection(
                    $sales->items()
                ),
            ];
        });
    }
    public function store(array $data, array $options = [
        'products' => [],
        'variations' => [],
        'applyAll' => false,
    ])
    {
        $sale = DB::transaction(function () use ($data, $options) {
            if ($options['applyAll']) {
                $products = $this->productRepository->all();
                $formattedProduct = $products->mapWithKeys(function ($product) {
                    return [
                        $product->id => [
                            'quantity' => $product->stock_qty,
                        ]
                    ];
                })->toArray();
                $variations = $this->variationRepository->all();
                $formattedVariation = $variations->mapWithKeys(function ($variation) {
                    return [
                        $variation->id => [
                            'quantity' => $variation->stock_qty,
                        ]
                    ];
                })->toArray();
              
                if (empty($data['is_active']) && $data['is_active'] === null) $data['is_active'] = 1;
                $sale = $this->repository->create($data);

                if (!$sale) throw new \Exception(__('messages.sale.error-can-not-sale'));
                $sale->products()->attach($formattedProduct);
                $sale->variations()->attach($formattedVariation);
                return $sale;
            } else {
                if (empty($data['is_active']) && $data['is_active'] === null) $data['is_active'] = 1;
                $sale = $this->repository->create($data);

                if (!$sale) throw new \Exception(__('messages.sale.error-can-not-sale'));
                if (isset($options['products'])) {
                    $sale->products()->attach($options['products']);
                }
                if (isset($options['variations'])) {
                    $sale->variations()->attach($options['variations']);
                }
                return $sale;
            }
        });
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return new SaleResource($this->loadRelationships($sale));
    }
    public function show(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('sale/' . $id . '?' . $allQuery, 60, function () use ($id) {
            $sale = $this->repository->find($id);
            if (!$sale) throw new ModelNotFoundException(__('messages.error-not-found'));
            return new SaleResource($this->loadRelationships($sale));
        });
    }
    public function update(int|string $id, array $data, array $options = [
        'products' => [],
        'variations' => []
    ])
    {
        $sale = DB::transaction(function () use ($id, $data, $options) {
            $sale = $this->repository->find($id);
            if (!$sale) throw new ModelNotFoundException(__('messages.error-not-found'));
            $sale->update($data);
            if (isset($options['products'])) {
                $sale->products()->sync($options['products']);
            }
            if (isset($options['variations'])) {
                $sale->variations()->sync($options['variations']);
            }
            return $sale;
        });
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return new SaleResource($this->loadRelationships($sale));
    }

    public function destroy(int|string $id)
    {
        $sale = $this->repository->find($id);
        if (!$sale) throw new ModelNotFoundException(__('messages.error-not-found'));
        $sale->delete();
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return true;
    }
    public function switchActive(int|string $id, bool|null $active)
    {
        $sale = $this->repository->find($id);
        if (!$sale) throw new ModelNotFoundException(__('messages.error-not-found'));
        Cache::tags([$this->cacheTag])->flush();
        if ($active) {
            $sale->is_active = $active;
            $sale->save();
        } else {
            $sale->is_active = false;
            $sale->save();
        }
    }
}
