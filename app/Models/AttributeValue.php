<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

class AttributeValue extends Model
{
    protected $fillable = ['value','attribute_id'];
    use HasFactory, SoftDeletes;
    public function attribute():BelongsTo
    {
        return $this->belongsTo(Attribute::class,'attribute_id');
    }
    public function variations():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'product_variation_attributes','attribute_value_id','variation_id');
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
