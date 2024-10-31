<?php

namespace App\Services\Attribute\Value;

use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\Attribute\Value\AttributeValueRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttributeValueService implements AttributeValueServiceInterface
{
    use CanLoadRelationships, Paginate;

    private array $relations = ['attribute', 'variations'];
    private array $columns = ['id','attribute_id', 'value', 'created_at', 'updated_at'];

    public function __construct(
        protected AttributeValueRepositoryInterface $repository,
        protected AttributeRepositoryInterface      $attributeRepository,
    )
    {
    }

    public function index(int|string $aid)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute)
            throw new ModelNotFoundException('Attribute not found');


        $values = $attribute->values()->sortByColumn(columns:$this->columns);
        return ValueResource::collection($this->loadRelationships($values)->get());
    }

    public function create(int|string $aid, string|array $data)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $attribute->values()->create($data);
        return new ValueResource($this->loadRelationships($value));
    }

    public function createMany(int|string $aid, array $data)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException('Attribute not found');
        $list = [];
        foreach ($data as $val) {
            if (isset($val)) {

                if (is_array($val)) {
                    if (isset($val['id']) && isset($val['value'])) {
                        $value = $attribute->values()->find($val['id']);
                        if ($value) {
                            $value->update([
                                'value' => $val['value']
                            ]);

                            $list[] = $value;
                        }
                    } elseif (isset($val['value'])) {
                        $value = $attribute->values()->create(['value' => $val['value']]);
                        $list[] = $value;
                    }
                }
            }
        }
        return ValueResource::collection($list);
    }

    public function find(int|string $aid, int|string $id)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $attribute->values()->find($id);
        if (!$value) throw new ModelNotFoundException('Attribute value not found');
        return new ValueResource($this->loadRelationships($value));
    }

    public function update(int|string $aid, int|string $id, array $data)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $attribute->values()->find($id);
        if (!$value) throw new ModelNotFoundException('Attribute value not found');
        $value->update($data);
        return new ValueResource($this->loadRelationships($value));
    }

    public function delete(int|string $aid, int|string $id)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException('Attribute not found');
        $value = $attribute->values()->find($id);
        if (!$value) throw new ModelNotFoundException('Attribute value not found');
        $value->delete();
        return true;
    }
}
