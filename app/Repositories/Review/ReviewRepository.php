<?php
namespace App\Repositories\Review;


use App\Repositories\BaseRepository;
use App\Models\Review;
use App\Models\User;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function findByProduct(int|string $productId)
    {
        return Review::where('product_id', $productId)->get();
    }

    public function getByLikes()
    {
        return Review::with(['user', 'product'])  
            ->withCount('likes')  
            ->orderBy('likes_count', 'desc')  
            ->get();  
    }

    public function toggleLike(int $review_id, int $user_id)
    {
        $review = Review::findOrFail($review_id);
        $user = User::findOrFail($user_id);

        // Thực hiện toggle like
        $user->likedReviews()->toggle($review_id);

        // Trả về số lượng like hiện tại của review
        return $review->likes()->count();
    }
    
}
