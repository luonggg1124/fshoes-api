<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\SoftDeletes;


class ProductVariations extends Model
{
    use HasFactory,softDeletes;
    protected $table = "product_variations";
    protected $fillable=[
        'product_id',
        'slug',
        'price',
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

    public function images():BelongsToMany
    {
        return $this->belongsToMany(Image::class,'product_variation_image','product_variation_id','image_id');
    }
}
