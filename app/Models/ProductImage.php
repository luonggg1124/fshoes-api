<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','product_variation_id','image_url','public_id','alt_text'];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function variation():BelongsTo
    {
        return $this->belongsTo(ProductVariations::class,'product_variation_id');
    }
}
