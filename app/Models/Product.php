<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'slug',
        'price',
        'sale_price',
        'is_sale',
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
    public function productImages():HasMany
    {
        return $this->hasMany(ProductImage::class);
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
