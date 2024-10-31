<?php 
namespace App\Repositories\Review;
use App\Repositories\BaseRepositoryInterface;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{


    public function findByProduct(int|string $productId);

    public function getByLikes();
    public function toggleLike(int $review_id, int $user_id);

}