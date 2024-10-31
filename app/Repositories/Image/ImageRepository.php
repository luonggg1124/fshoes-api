<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepository;


class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function __construct(Image $model)
    {
        parent::__construct($model);
    }
}
