<?php

namespace App\Services\Attribute;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Attribute\AttributeRepositoryInterface;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;


class AttributeService implements AttributeServiceInterface
{
    use CanLoadRelationships, Paginate;
    private array $relations = ['values', 'product','attributes_values'];
    protected string $allQueryUrl;
    protected string $cacheTag = 'attributes';
    private array $columns = ['id', 'value', 'attribute_id', 'created_at', 'updated_at'];
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository,
    ) {
        $this->allQueryUrl = http_build_query(request()->query());
    }
    public function all()
    {
        return Cache::tags([$this->cacheTag])->remember('all/attributes?' . $this->allQueryUrl, 60, function () {
            $perPage = request()->query('per_page');
            $attributes = $this->loadRelationships($this->attributeRepository->query()->sortByColumn(columns: $this->columns))->paginate($perPage);
            return [
                'paginator' => $this->paginate($attributes),
                'data' => AttributeResource::collection($attributes->items()),
            ];
        });
    }

    public function create(array $data)
    {
        $attribute = $this->attributeRepository->create($data);
        if (!$attribute) throw new Exception(__('messages.attribute.error-can-not-attribute'));
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return new AttributeResource($this->loadRelationships($attribute));
    }

    public function find(int|string $id)
    {
        return Cache::tags([$this->cacheTag])->remember('attribute/' . $id . '?' . $this->allQueryUrl, 60, function () use ($id) {
            $attribute = $this->attributeRepository->find($id);
            if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
            return new AttributeResource($this->loadRelationships($attribute));
        });
    }
    public function update(int|string $id, array $data)
    {
        $attribute = $this->attributeRepository->find($id);
        if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
        $attribute->update($data);
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return new AttributeResource($this->loadRelationships($attribute));
    }
    public function delete(int|string $id)
    {
        $attribute = $this->attributeRepository->find($id);
        if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
        $values = $attribute->values;
        if(count($values) > 0){
            foreach($values as $val){
                $variations = $val->variations;
                if(count($variations) > 0){
                    throw new \InvalidArgumentException(__('messages.error-delete-attribute-variations'));
                }
            }
        }
        $attribute->values()->delete();
        $attribute->delete();
        Cache::tags([$this->cacheTag,...$this->relations])->flush();
        return true;
    }
    public function isFilterAttributes()
    {
        return Cache::tags([$this->cacheTag])->remember('filter/attribute?' . $this->allQueryUrl, 60, function () {
            $attributes = $this->attributeRepository->query()->where('is_filter', true)->get();
            $attributes->load(['values']);
            return AttributeResource::collection($attributes);
        });
    }
}
