<?php

namespace App\Services\Sale;

use App\Http\Resources\Sale\SaleResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Sale\SaleRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SaleService implements SaleServiceInterface
{
    use CanLoadRelationships,Paginate;
    private array $relations = ['products','variations'];
    private array $columns = ['id','name','type','start_date','end_date','created_at','updated_at','deleted_at'];
    public function __construct(
        protected SaleRepositoryInterface $repository
    )
    {
    }

    public function all()
    {
        $perPage = request()->query('per_page');

        $sales = $this->loadRelationships($this->repository->query()->sortByColumn(columns:$this->columns))->paginate($perPage);
        return [
            'paginator' => $this->paginate($sales),
            'data' => SaleResource::collection(
                $sales->items()
            ),
        ];
    }
    public function store(array $data,array $options = [
        'products' => [],
        'variations' => []
    ]){
        if(empty($data['is_active'])) $data['is_active'] = 1;
        $sale = $this->repository->create($data);
        if(!$sale) throw new \Exception("Can't create sale");
        if(isset($options['products']) && is_array($options['products'])) {
            $sale->products()->attach($options['products']);
        }
        if(isset($options['variations']) && is_array($options['variations'])) {
            $sale->variations()->attach($options['variations']);
        }
        return new SaleResource($this->loadRelationships($sale));
    }
    public function show(int|string $id)
    {
        $sale = $this->repository->find($id);
        if(!$sale) throw new ModelNotFoundException('Sale not found');
        return new SaleResource($this->loadRelationships($sale));
    }
    public function update(int|string $id,array $data,array $options = [
        'products' => [],
        'variations' => []
    ])
    {
        $sale = $this->repository->find($id);

        if(!$sale) throw new ModelNotFoundException('Sale not found');
        $sale->update($data);
        if(isset($options['products']) && is_array($options['products'])) {
            $sale->products()->sync($options['products']);
        }
        if(isset($options['variations']) && is_array($options['variations'])) {
            $sale->variations()->sync($options['variations']);
        }
        return new SaleResource($this->loadRelationships($sale));
    }

    public function destroy(int|string $id)
    {
        $sale = $this->repository->find($id);
        if(!$sale) throw new ModelNotFoundException('Sale not found');
        $sale->delete();
        return true;
    }
}
