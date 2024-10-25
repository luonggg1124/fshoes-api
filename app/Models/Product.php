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
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'name',
        'slug',
        'price',
        'image_url',
        'short_description',
        'description',
        'sku',
        'status',
        'qty_sold',
        'stock_qty',

    ];
    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class,'category_product','product_id','category_id');
    }
    public function images():BelongsToMany
    {
        return $this->belongsToMany(Image::class,'product_image','product_id','image_id');
    }
    public function discounts():BelongsToMany
    {
        return $this->belongsToMany(Discount::class,'product_discount','product_id','discount_id')->withPivot('quantity');
    }
    public function currentDiscount()
    {
      return $this->discounts()->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())->first();

    }
    public function salePrice(){
        $discount = $this->currentDiscount();
        if($discount){
            if($discount->value == 'percent') return $this->price - ($this->price*$discount->value/100);
            else return $this->price - $discount->value;
        }else{
            return $this->price;
        }
    }

    public function variations():HasMany
    {
        return $this->hasMany(ProductVariations::class);
    }
    public function ownAttributes():HasMany
    {
        return $this->hasMany(Attribute::class);
    }
    public function likedBy():BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_product','product_id','user_id');
    }
    public function orderDetails():HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
    public function orders():HasManyThrough
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
    public function reviews():HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeSortByColumn(QueryBuilder|EloquentBuilder $query,array $columns = [],string $defaultColumn = 'updated_at',string $defaultSort = 'desc'):QueryBuilder|EloquentBuilder
    {
        $sort = request()->query('sort');
        $column = request()->query('column');
        if(!in_array($sort,['asc','desc'])) $sort = $defaultSort;
        if(!in_array($column,$columns)) $column = $defaultColumn;
       return $query->orderBy($column,$sort);
    }
}
