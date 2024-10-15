<?php

namespace App\Repositories\Groups;

use App\Models\Groups;
use App\Repositories\BaseRepository;
use \App\Repositories\Groups\GroupsRepositoryInterface;


class GroupsRepository extends BaseRepository implements GroupsRepositoryInterface
{
    public function __construct(Groups $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
