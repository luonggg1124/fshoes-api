<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'import_price',
        'image_url',
        'short_description',
        'description',
        'sku',
        'status',
        'qty_sold',
        'stock_qty',

    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'product_image', 'product_id', 'image_id');
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'product_sale', 'product_id', 'sale_id')->withPivot('quantity');
    }

    public function saleQuantity()
    {
        $discount = $this->currentSale();
        if ($discount) return $discount->original['pivot_quantity'];
        return 0;
    }

    public function currentSale()
    {
        return $this->sales()->wherePivot('quantity', '>', 0)->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())->orderByRaw("CASE
                    WHEN type = 'percent' THEN value* ? / 100
                    WHEN type = 'fixed' THEN value
                  END DESC", [$this->price])
            ->first();
    }

    public function salePrice()
    {
        $discount = $this->currentSale();

        if ($discount) {
            if ($discount->type == 'percent') {
                return $this->price - ($this->price * $discount->value / 100);
            }
            else{
                return $this->price - $discount->value;}
        } else {
            return null;
        }
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariations::class);
    }

    public function ownAttributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_product', 'product_id', 'user_id');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(
            Order::class,
            OrderDetails::class,
            'product_id',
            'id',
            'id',
            'order_id'
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function averageRating(){
        $rating = $this->reviews()->average('rating');
        return $rating ? round($rating) : 0;
    }

    public function scopeSortByColumn(QueryBuilder|EloquentBuilder $query, array $columns = [], string $defaultColumn = 'updated_at', string $defaultSort = 'desc'): QueryBuilder|EloquentBuilder
    {
        $sort = request()->query('sort');
        $column = request()->query('column');
        if (!in_array($sort, ['asc', 'desc'])) $sort = $defaultSort;
        if (!in_array($column, $columns)) $column = $defaultColumn;
        return $query->orderBy($column, $sort);
    }
}
