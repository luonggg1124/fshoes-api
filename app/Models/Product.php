<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function variations():HasMany
    {
        return $this->hasMany(ProductVariations::class);
    }

    public function reviews():HasMany
    {
        return $this->hasMany(Review::class);
    }
}
