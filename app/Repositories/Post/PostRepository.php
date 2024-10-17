<?php

namespace App\Repositories\Post;

use App\Models\Posts;
use App\Repositories\BaseRepository;
use App\Repositories\Topic\TopicsRepositoryInterface;


class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function __construct(Posts $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
