<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;


class Category extends Model
{
    use HasFactory,softDeletes;
    protected $fillable = ['name', 'slug', 'parent_id','image_url', 'public_id'];
    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_relation', 'child_id', 'parent_id');
    }
    public function children():BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_relation','parent_id','child_id');
    }
    public function interestedBy():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interests', 'category_id', 'user_id');
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
