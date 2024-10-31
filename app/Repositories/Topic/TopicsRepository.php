<?php

namespace App\Repositories\Topic;

use App\Models\Topics;
use App\Repositories\BaseRepository;
use \App\Repositories\Topic\TopicsRepositoryInterface;


class TopicsRepository extends BaseRepository implements TopicsRepositoryInterface
{
    public function __construct(Topics $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
