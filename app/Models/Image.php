<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['url','public_id','alt_text'];

    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_image','image_id','product_id');
    }
    public function variations():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'product_variation_image','image_id','product_variation_id');

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
