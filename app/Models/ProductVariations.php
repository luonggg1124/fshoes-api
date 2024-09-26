<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariations extends Model
{
    use HasFactory,softDeletes;
    protected $table = "product_variations";
    protected $fillable=[
        'product_id',
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
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images():HasMany
    {
        return $this->hasMany(ProductImage::class,'product_variation_id');
    }
}
