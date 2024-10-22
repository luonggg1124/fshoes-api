<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    use HasFactory;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_discount','discount_id','product_id');
    }

    public function variations():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'variation_discount','discount_id','variation_id');
    }
}
