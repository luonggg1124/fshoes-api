<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'title',
        'text',
        'rating',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'review_like', 'review_id', 'user_id');
    }
    public function scopeSortByColumn(QueryBuilder|EloquentBuilder $query, array $columns = [], string $defaultColumn = 'updated_at', string $defaultSort = 'desc'): QueryBuilder|EloquentBuilder
    {
        $sort = request()->query('sort');
        $column = request()->query('column');
        if (!in_array($sort, ['asc', 'desc']))
            $sort = $defaultSort;
        if (!in_array($column, $columns))
            $column = $defaultColumn;
        return $query->orderBy($column, $sort);
    }

  public static function canReview($userId, $productId)
{
    // Kiểm tra nếu người dùng đã mua sản phẩm này trong đơn hàng có trạng thái 
    $hasBought = OrderDetails::where('product_id', $productId)
        ->whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->whereIn('status', [2,4]); 
        })
        ->exists();

    // Kiểm tra nếu người dùng đã đánh giá sản phẩm này
    $alreadyReviewed = self::where('user_id', $userId)
        ->where('product_id', $productId)
        ->exists();

    // Người dùng phải đã mua sản phẩm và chưa đánh giá sản phẩm này
    return $hasBought && !$alreadyReviewed;
}
}
