<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        "user_id",
        "product_variation_id",
        "product_id",
        "quantity"
    ];

    public function product() {
        return $this->belongsTo(Product::class , 'product_id' , 'id');
    }
    public function product_variation() {
        return $this->belongsTo(ProductVariations::class , 'product_variation_id' , 'id');
    }
}
