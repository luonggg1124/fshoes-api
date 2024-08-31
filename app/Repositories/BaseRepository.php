<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function all():? array
    {
        return $this->model->all()->toArray();
    }

    public function find(int $id):? array
    {

        return $this->model->query()->findOrFail($id)?->toArray();
    }

    public function create(array $data):? array
    {
        return $this->model->query()->create($data)->toArray();
    }

    public function update(array $data, $id):? array
    {
        $record = $this->model->query()->find($id);
        if($record){
            $record->update($data);
            return $record;
        }
        return null;
    }
    public function delete(int $id):? bool
    {
        $record = $this->model->query()->find($id);
        if($record){
            return $record->delete();
        }
        return false;
    }
}
