<?php

namespace App\Services\Attribute\Value;

use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\Attribute\Value\AttributeValueRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        // $errors = [];
        // $attribute = $this->attributeRepository->find($aid);
        // if (!$attribute) {
        //     $errors[] = "Không tìm thấy thuộc tính";
        // } else {
        //     if (isset($data['value'])) {
        //         $existing = $attribute->values()->where('value', $data['value'])->exists();
        //         if ($existing) {
        //             $errors[] = $attribute->name . ' đã tồn tại giá trị ' . $data['value'];
        //         }
        //         $value = $attribute->values()->create($data);
        //     } else {
        //         $errors[] = $data['value'] . ' không hợp lệ';
        //     }
        // }
        // return [
        //     'value' => new ValueResource($this->loadRelationships($value)),
        //     'errors' => $errors
        // ];
    }

    public function createMany(int|string $aid, array $data)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
        if (empty($data)) throw new \InvalidArgumentException(__('messages.invalid-value'));
        $errors = [];
        $listDataCreated = [];
        foreach ($data as $value) {
            $existed = $attribute->values()->where('value', $value)->exists();
            if (!is_string($value)) {
                $errors[] = $value . ' không hợp lệ';
            } else if ($existed) {
                $errors[] = $attribute->name . ' đã tồn tại giá trị ' . $value;
            } else {
                $listDataCreated[] = [
                    'attribute_id' => $aid,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        $success = DB::transaction(function () use ($listDataCreated) {
            if(empty($listDataCreated)) return true;
            return DB::table('attribute_values')->insert($listDataCreated);
        });
        if (!$success) throw new Exception('Đã có lỗi xảy ra!Các giá trị sẽ không được khởi tạo');
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return [
            'errors' => $errors
        ];
    }

    public function find(int|string $aid, int|string $id): ValueResource
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
        // $attribute = $this->attributeRepository->find($aid);
        // if (!$attribute) throw new ModelNotFoundException(__('messages.error-not-found'));
        // $value = $attribute->values()->find($id);
        // if (!$value) throw new ModelNotFoundException(__('messages.error-not-found'));
        // $errors = [];

        // if (isset($data['value'])) {
        //     $existing = $attribute->values()->where('value', $data['value'])->exists();
        //     if ($existing) {
        //         $errors[] = $attribute->name . ' đã tồn tại giá trị ' . $data['value'];
        //     }
        //     $value = $value->update($data);
        // } else {
        //     $errors[] = $data['value'] . ' không hợp lệ';
        // }
        // $value->update($data);
        // Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        // return [
        //     'value' => new ValueResource($this->loadRelationships($value)),
        //     'errors' => $errors
        // ];
    }

    public function delete(int|string $aid, int|string $id)
    {
        $attribute = $this->attributeRepository->find($aid);
        if (!$attribute) throw new ModelNotFoundException(message: __('messages.error-not-found'));
        $value = $attribute->values()->find($id);
        if (!$value) throw new ModelNotFoundException(message: __('messages.error-not-found'));
        $variationsQuantity = $value->variations()->withTrashed()->count();
        if ($variationsQuantity > 0) {
            throw new \InvalidArgumentException(__('messages.error-delete-attribute-variations'));
        }
        $value->forceDelete();
        Cache::tags([$this->cacheTag, ...$this->relations])->flush();
        return true;
    }
}
