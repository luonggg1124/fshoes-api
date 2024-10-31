<?php
namespace App\Services\Review;

use App\Http\Resources\Review\ReviewResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Review\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReviewService implements ReviewServiceInterface
{

    use CanLoadRelationships, Paginate;
    private array $relations = ['user', 'product'];
    public function __construct(protected ReviewRepositoryInterface $reviewRepository)
    {

    }

    // Lấy tất cả reviews
    public function all()
    {
        return $this->reviewRepository->all();
    }

    // Thêm review
    public function create(array $data)
    {
        $review = $this->reviewRepository->create($data);
        return new ReviewResource($this->loadRelationships($review));
    }

    // Lấy review theo ID
    public function find(int|string $id)
    {
        $review = $this->reviewRepository->find($id);
        if (!$review)
            throw new ModelNotFoundException('Review not found');
        return new ReviewResource($this->loadRelationships($review));
    }

    // Sửa review
    public function update(int|string $id, array $data)
    {

        $review = $this->reviewRepository->find($id);
        if (!$review)
            throw new ModelNotFoundException('Review not found');
        $updated = $review->update($data);
        if ($updated) {
            return new ReviewResource($this->loadRelationships($review));
        }
        return null;
    }

    // Xóa review
    public function delete(int|string $id)
    {
        $review = $this->reviewRepository->find($id);
        if (!$review)
            throw new ModelNotFoundException('Review not found');
        $review->delete();
        return true;
    }

    public function getProduct(int|string $productId)
    {
        // Lấy tất cả các review của sản phẩm
        $reviews = $this->reviewRepository->findByProduct($productId);

        // Nạp các quan hệ cho từng review (ví dụ: user, product)
        return $this->loadRelationships($reviews);
    }

    public function getByLikes()
    {
        $reviews = $this->reviewRepository->getByLikes();
        return $this->loadRelationships($reviews);  // Nạp các quan hệ
    }

    public function toggleLike(int $review_id, int $user_id)
    {
        // Lấy review từ repository
        $review = $this->reviewRepository->find($review_id);
        if (!$review) {
            throw new ModelNotFoundException('Review not found');
        }

        return $this->reviewRepository->toggleLike($review_id, $user_id);
    }
}
