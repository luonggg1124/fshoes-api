<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Discount extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','type','value','start_date','end_date','is_active'];
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_discount','discount_id','product_id')->withPivot('quantity');
    }

    public function variations():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'variation_discount','discount_id','variation_id')->withPivot('quantity');
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
