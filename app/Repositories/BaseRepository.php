<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->query()->find($id);
    }

    public function create(array $data)
    {
        return $this->model->query()->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->query()->find($id);
        if(!$record){
            throw new ModelNotFoundException('Record not found');
        }
        $record->update($data);
        return $record;
    }
    public function delete(int $id)
    {
        $record = $this->model->query()->find($id);
        if($record){
            return $record->delete();
        }
        return false;
    }
    public function query()
    {
        return $this->model;
    }
}