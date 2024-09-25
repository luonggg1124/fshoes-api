<?php

namespace App\Services\Attribute\Value;

use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\Attribute\Value\AttributeValueRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttributeValueService  implements AttributeValueServiceInterface
{
    use CanLoadRelationships,Paginate;
    private array $relations = ['attribute','productVariation'];
    public function __construct(
        protected AttributeValueRepositoryInterface $repository,
        protected AttributeRepositoryInterface $attributeRepository,
    ){}

    public function all()
    {
        $perPage = request()->query('per_page');
        $column = request()->query('column') ?? 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $values = $this->loadRelationships($this->repository->query()->orderBy($column, $sort))->paginate($perPage);
        return [
            'paginator' => $this->paginate($values),
            'data' => $values->items(),
        ];
    }
    public function create(int|string $aid,array $data){
        $attribute = $this->attributeRepository->find($aid);
        if(!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $attribute->values()->create($data);
        return $this->loadRelationships($value);
    }
    public function find(int|string $aid,int|string $id){
        $attribute = $this->attributeRepository->find($aid);
        if(!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $this->repository->find($id);
        if(!$value) throw new ModelNotFoundException('Attribute not found');
        return $this->loadRelationships($value);
    }
    public function update(int|string $id, array $data){
        $attribute = $this->attributeRepository->find($aid);
        if(!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $this->repository->find($id);
        if(!$value) throw new ModelNotFoundException('Attribute not found');
        $value->update($data);
        return $this->loadRelationships($value);
    }
    public function delete(int|string $id){
        $value = $this->repository->find($id);
        if(!$value) throw new ModelNotFoundException('Attribute not found');
        $value->delete();
        return true;
    }
}
