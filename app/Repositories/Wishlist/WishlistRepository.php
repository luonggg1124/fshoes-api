<?php
namespace App\Repositories\Wishlist;
use App\Models\Wishlist;
use App\Repositories\BaseRepository;
use App\Repositories\Order\OrderRepositoryInterface;

class WishlistRepository extends BaseRepository implements WishlistRepositoryInterface  {
    public function __construct(Wishlist $model)
    {
        parent::__construct($model);
    }
}
