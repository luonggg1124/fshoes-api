<?php
namespace App\Repositories\Cart;
use App\Models\Cart;
use App\Repositories\BaseRepository;

class CartRepository extends BaseRepository implements CartRepositoryInterface  {
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }
}