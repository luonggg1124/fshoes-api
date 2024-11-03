<?php
namespace App\Services\Review;

use App\Http\Resources\Review\ReviewResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Paginate;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Review\ReviewRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReviewService implements ReviewServiceInterface
{

    use CanLoadRelationships, Paginate;
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
    )
    {

    }

    // Lấy tất cả reviews
    public function all()
    {
        $perPage = request()->query('per_page');
        $reviews = $this->loadRelationships($this->reviewRepository->query()->sortByColumn(columns:$this->columns))->paginate($perPage);
        return [
            'paginator' => $this->paginate($reviews),
            'data' => ReviewResource::collection($reviews->items())
        ];
    }

    // Thêm review
    public function create(array $data)
    {
        if($data['product_id']){
            $product = $this->productRepository->find($data['product_id']);
            if(!$product) throw  new ModelNotFoundException('Product not found');
        }
        $user = \request()->user();

        if(!$user) throw new AuthorizationException("Unauthorized!");
        $data['user_id'] = $user->id;
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
        if($data['product_id']){
            $product = $this->productRepository->find($data['product_id']);
            if(!$product) throw new ModelNotFoundException('Product not found');
        }
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
        $requestUser = \request()->user();
        if($requestUser->id != $review->user_id) throw new AuthorizationException("Unauthorized!");
        $review->delete();
        return true;
    }
    public function reviewsByProduct(int|string $productId)
    {
        $product = $this->productRepository->find($productId);
        if (!$product) throw new ModelNotFoundException('Product not found');
        $reviews = $product->reviews()->with(['user'])->get();
        return ReviewResource::collection($reviews);
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
            throw new ModelNotFoundException('Review not found');
        }

        return $this->reviewRepository->toggleLike($review_id, $user_id);
    }
}
