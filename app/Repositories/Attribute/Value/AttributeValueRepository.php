<?php

namespace App\Repositories\Attribute\Value;

use App\Models\AttributeValue;
use App\Repositories\BaseRepository;



class AttributeValueRepository extends BaseRepository implements AttributeValueRepositoryInterface
{
    public function __construct(
        AttributeValue $model
    )
    {
        parent::__construct($model);
    }


}
