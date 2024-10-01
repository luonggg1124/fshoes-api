<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;


    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_image','image_id','product_id',);
    }
    public function variations():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'product_variation_image','image_id','product_variation_id');
    }
}
