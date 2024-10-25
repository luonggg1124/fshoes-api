<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;


class ProductVariations extends Model
{
    use HasFactory;
    protected $table = "product_variations";
    protected $fillable=[
        'product_id',
        'slug',
        'price',
        'sku',
        'status',
        'qty_sold',
        'stock_qty',
    ];
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images():BelongsToMany
    {
        return $this->belongsToMany(Image::class,'product_variation_image','product_variation_id','image_id');
    }
    public function values():BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class,'product_variation_attributes','variation_id','attribute_value_id');
    }
    public function discounts():BelongsToMany
    {
        return $this->belongsToMany(Discount::class,'variation_discount','variation_id','discount_id')->withPivot('quantity');
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
            if($discount->type === 'percent') return $this->price - ($this->price*$discount->value/100);
            else return $this->price - $discount->value;
        }else{
            return $this->price;
        }

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
