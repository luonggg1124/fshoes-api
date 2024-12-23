<?php

namespace App\Services\Attribute\Value;

use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\Attribute\Value\AttributeValueRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class AttributeValueService implements AttributeValueServiceInterface
{
    use CanLoadRelationships, Paginate;

    protected string $cacheTag = 'attributes_values';
    protected string $allQueryUrl;
    private array $relations = ['attribute', 'variations', 'attributes'];
    private array $columns = ['id', 'attribute_id', 'value', 'created_at', 'updated_at'];

    public function __construct(
        protected AttributeValueRepositoryInterface $repository,
        protected AttributeRepositoryInterface      $attributeRepository,
    ) {
        $this->allQueryUrl = http_build_query(request()->query());
    }

    public function index(int|string $aid)
    {
        return Cache::tags([$this->cacheTag])->remember('all/attributes?' . $this->allQueryUrl, 60, function () use ($aid) {
            $attribute = $this->attributeRepository->find($aid);
            if (!$attribute)
                throw new ModelNotFoundException(__('messages.error-not-found'));
            $values = $attribute->values()->sortByColumn(columns: $this->columns);
            return ValueResource::collection($this->loadRelationships($values)->get());
        });
    }

    public function create(int|string $aid, string|array $data)
    {
        $errors = [];
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) {
            $errors[] = "Không tìm thấy thuộc tính";
        } else {
            if (isset($data['value'])) {
                $existing = $attribute->values()->where('value', $data['value'])->exists();
                if ($existing) {
                    $errors[] = $attribute->name . ' đã tồn tại giá trị ' . $data['value'];
                }
                $value = $attribute->values()->create($data);
            } else {
                $errors[] = $data['value'] . ' không hợp lệ';
            }
        }


        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return [
            'value' => new ValueResource($this->loadRelationships($value)),
            'errors' => $errors
        ];
    }

    public function createMany(int|string $aid, array $data)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
        $list = [];

        if (empty($data)) throw new \InvalidArgumentException(__('messages.invalid-value'));
        $errors = [];
        foreach ($data as $val) {
            if (isset($val)) {
                if (is_array($val)) {
                    if (isset($val['id']) && isset($val['value'])) {
                        $value = $this->update($aid, $val['id'], [
                            'value' => $val['value']
                        ]);
                        $list[] = $value['value'];
                        $errors[] = $value['errors'];
                    } elseif (isset($val['value'])) {
                        $value = $this->create($aid, ['value' => $val['value']]);
                        $list[] = $value['value'];
                        $errors[] = $value['errors'];
                    }
                }
            }
        }
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return [
            'values' => ValueResource::collection($list),
            'errors' => $errors
        ];
    }

    public function find(int|string $aid, int|string $id)
    {
        return Cache::tags([$this->cacheTag])
            ->remember('attribute' . $aid . '/' . 'value/' . $id . '?' . $this->allQueryUrl, 60, function () use ($aid, $id) {
                $attribute = $this->attributeRepository->find($aid);
                if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
                $value = $attribute->values()->find($id);
                if (!$value) throw new ModelNotFoundException(__('messages.error-not-found'));
                return new ValueResource($this->loadRelationships($value));
            });
    }

    public function update(int|string $aid, int|string $id, array $data)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
        $value = $attribute->values()->find($id);
        if (!$value) throw new ModelNotFoundException(__('messages.error-not-found'));
        $errors = [];

        if (isset($data['value'])) {
            $existing = $attribute->values()->where('value', $data['value'])->exists();
            if ($existing) {
                $errors[] = $attribute->name . ' đã tồn tại giá trị ' . $data['value'];
            }
            $value = $value->update($data);
        } else {
            $errors[] = $data['value'] . ' không hợp lệ';
        }
        $value->update($data);
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return [
            'value' => new ValueResource($this->loadRelationships($value)),
            'errors' => $errors
        ];
    }

    public function delete(int|string $aid, int|string $id)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException(message: __('messages.error-not-found'));
        $value = $attribute->values()->find($id);
        if (!$value) throw new ModelNotFoundException(message: __('messages.error-not-found'));
        if (count([...$value->variations]) > 0) {
            throw new \InvalidArgumentException(__('messages.error-delete-attribute-variations'));
        }

        $value->delete();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return true;
    }
}
