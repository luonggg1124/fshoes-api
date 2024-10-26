<?php

namespace App\Services\Discount;

use App\Http\Resources\Discount\DiscountResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Discount\DiscountRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class DiscountService implements DiscountServiceInterface
{
    use CanLoadRelationships,Paginate;
    private array $relations = ['products','variations'];
    private array $columns = ['id','name','type','start_date','end_date','created_at','updated_at','deleted_at'];
    public function __construct(
        protected DiscountRepositoryInterface  $repository
    )
    {
    }

    public function all()
    {
        $perPage = request()->query('per_page');

        $discounts = $this->loadRelationships($this->repository->query()->sortByColumn(columns:$this->columns))->paginate($perPage);
        return [
            'paginator' => $this->paginate($discounts),
            'data' => DiscountResource::collection(
                $discounts->items()
            ),
        ];
    }
    public function store(array $data,array $options = [
        'products' => [],
        'variations' => []
    ]){
        if(empty($data['is_active'])) $data['is_active'] = 1;
        $discount = $this->repository->create($data);
        if(!$discount) throw new \Exception("Can't create sale");
        if(isset($options['products']) && is_array($options['products'])) {
            $discount->products()->attach($options['products']);
        }
        if(isset($options['variations']) && is_array($options['variations'])) {
            $discount->variations()->attach($options['variations']);
        }
        return new DiscountResource($this->loadRelationships($discount));
    }
    public function show(int|string $id)
    {
        $discount = $this->repository->find($id);
        if(!$discount) throw new ModelNotFoundException('Discount not found');
        return new DiscountResource($this->loadRelationships($discount));
    }
    public function update(int|string $id,array $data,array $options = [
        'products' => [],
        'variations' => []
    ])
    {
        $discount = $this->repository->find($id);

        if(!$discount) throw new ModelNotFoundException('Discount not found');
        $discount->update($data);
        if(isset($options['products']) && is_array($options['products'])) {
            $discount->products()->sync($options['products']);
        }
        if(isset($options['variations']) && is_array($options['variations'])) {
            $discount->variations()->sync($options['variations']);
        }
        return new DiscountResource($this->loadRelationships($discount));
    }
}
