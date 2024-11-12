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
        'import_price',
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
    public function sales():BelongsToMany
    {
        return $this->belongsToMany(Sale::class,'variation_sale','variation_id','sale_id')->withPivot('quantity');
    }
    public function currentSale()
    {

        return $this->sales()->wherePivot('quantity','>',0)->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())->orderBy('created_at','desc')->first();
    }
    public function salePrice(){
        $discount = $this->currentSale();
        if($discount){
            if($discount->type === 'percent') return $this->price - ($this->price*$discount->value/100);
            else return $this->price - $discount->value;
        }else{
            return null;
        }

    }
    public function saleQuantity()
    {
        $discount = $this->currentSale();
        if($discount)  return $discount->original['pivot_quantity'];
        return 0;
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
