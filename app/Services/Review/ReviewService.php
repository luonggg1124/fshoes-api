<?php

namespace App\Services\Review;

use App\Http\Resources\Review\ReviewResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Review\ReviewRepositoryInterface;
use App\Models\OrderDetails;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;


class ReviewService implements ReviewServiceInterface
{

    use CanLoadRelationships, Paginate;
    protected $cacheTag = 'reviews';
    private array $relations = ['user', 'product'];
    private array $columns = [
        'id',
        'title',
        'text',
        'rating',
        'created_at',
        'updated_at',
    ];
    public function __construct(
        protected ReviewRepositoryInterface $reviewRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    // Lấy tất cả reviews
    public function all()
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('all-reviews?' . $allQuery, 60, function () {
            $perPage = request()->query('per_page');
            $reviews = $this->loadRelationships($this->reviewRepository->query()->sortByColumn(columns: $this->columns))->paginate($perPage);
            return [
                'paginator' => $this->paginate($reviews),
                'data' => ReviewResource::collection($reviews->items())
            ];
        });
    }

    // Thêm review

    public function create(array $data)
    {
        $user = \request()->user();
        $productId = $data['product_id'];

        // Kiểm tra xem người dùng có thể đánh giá sản phẩm này không
        if (!$this->canReview($user->id, $productId)) {
            throw new \Exception(__('messages.review.error-review-forbidden'));
        }

        // Kiểm tra sản phẩm tồn tại
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }

        $data['user_id'] = $user->id;
        $review = $this->reviewRepository->create($data);
        if (!$review) {
            throw new Exception(__('messages.delete-success'));
        }
        Cache::tags([$this->cacheTag])->flush();
        return new ReviewResource($this->loadRelationships($review));
    }


    public function find(int|string $id)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('review/' . $id . '?' . $allQuery, 60, function () use ($id) {
            $review = $this->reviewRepository->find($id);
            if (!$review)
                throw new ModelNotFoundException(__('messages.error-not-found'));
            return new ReviewResource($this->loadRelationships($review));
        });
    }

    // Sửa review
    public function update(int|string $id, array $data)
    {

        $review = $this->reviewRepository->find($id);
        if (!$review)
            throw new ModelNotFoundException(__('messages.error-not-found'));

        $updated = $review->update($data);
        Cache::tags([$this->cacheTag])->flush();
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
            throw new ModelNotFoundException(__('messages.error-not-found'));
        //        $requestUser = \request()->user();
        //        if($requestUser->id != $review->user_id) throw new AuthorizationException("Unauthorized!");
        $review->delete();
        Cache::tags([$this->cacheTag])->flush();
        return true;
    }
    public function reviewsByProduct(int|string $productId)
    {
        $allQuery = http_build_query(request()->query());
        return Cache::tags([$this->cacheTag])->remember('reviews-by-product/' . $productId . '?' . $allQuery, 60, function () use ($productId) {
            $product = $this->productRepository->find($productId);
            if (!$product)
                throw new ModelNotFoundException(__('messages.error-not-found'));
            $reviews = $product->reviews()->with(['user'])->get();
            return ReviewResource::collection($reviews);
        });
    }
    public function getProduct(int|string $productId)
    {
        // Lấy tất cả các review của sản phẩm
        $reviews = $this->reviewRepository->findByProduct($productId);

        // Nạp các quan hệ cho từng review (ví dụ: user, product)
        return ReviewResource::collection($this->loadRelationships($reviews));
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
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }

        return $this->reviewRepository->toggleLike($review_id, $user_id);
    }

    public function canReview(int $userId, int $productId)
    {

        $hasBought = OrderDetails::where('product_id', $productId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', '>=', 4);
            })->exists();


        $alreadyReviewed = $this->reviewRepository->findByUserAndProduct($userId, $productId);


        return $hasBought && !$alreadyReviewed;
    }
}
