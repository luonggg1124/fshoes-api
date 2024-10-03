<?php
namespace App\Services\Attribute;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Attribute\AttributeRepositoryInterface;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class AttributeService implements AttributeServiceInterface
{
    use CanLoadRelationships,Paginate;
    private array $relations = ['values'];
    private array $columns = ['value','attribute_id','created_at','updated_at'];
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository,

    )
    {
    }
    public function all()
    {
        $perPage = request()->query('per_page');
        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $attributes = $this->loadRelationships($this->attributeRepository->query()->orderBy($column, $sort))->paginate($perPage);
        return [
            'paginator' => $this->paginate($attributes),
            'data' => AttributeResource::collection($attributes->items()),
        ];
    }
    public function create(array $data)
    {
        $attribute = $this->attributeRepository->create($data);
        return new AttributeResource($this->loadRelationships($attribute));
    }
    public function find(int|string $id){
        $attribute = $this->attributeRepository->find($id);
        if(!$attribute) throw new ModelNotFoundException('Attribute not found');
        return new AttributeResource($this->loadRelationships($attribute));
    }
    public function update(int|string $id, array $data){
        $attribute = $this->attributeRepository->find($id);
        if(!$attribute) throw new ModelNotFoundException('Attribute not found');
        $attribute->update($data);
        return new AttributeResource($this->loadRelationships($attribute));
    }
    public function delete(int|string $id){
        $attribute = $this->attributeRepository->find($id);
        if(!$attribute) throw new ModelNotFoundException('Attribute not found');
        $attribute->values()->delete();
        $attribute->delete();
            return true;
    }
}
