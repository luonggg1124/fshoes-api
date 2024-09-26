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
    private array $columns = ['attribute_id','value','created_at','updated_at'];
    public function __construct(
        protected AttributeValueRepositoryInterface $repository,
        protected AttributeRepositoryInterface $attributeRepository,
    ){}

    public function index(int|string $aid)
    {
        $attribute = $this->attributeRepository->find($aid);
        if(!$attribute)
            throw new ModelNotFoundException('Attribute not found');

        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $values = $attribute->values()->orderBy($column, $sort);
        return $this->loadRelationships($values)->get();
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
        $value = $attribute->values()->find($id);
        if(!$value) throw new ModelNotFoundException('Attribute value not found');
        return $value;
    }
    public function update(int|string $aid,int|string $id, array $data){
        $value = $this->find($aid,$id);
        $value->update($data);
        return $this->loadRelationships($value);
    }
    public function delete(int|string $aid,int|string $id){
        $value = $this->find($aid,$id);
        $value->delete();
        return true;
    }
}
