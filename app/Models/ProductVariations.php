<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductVariations extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "product_variations";
    protected $fillable=[
        'product_id',
        'sku',
        'price',
        'sale_price',
        'is_sale',
        'stock_qty',
        'stock_sold',
        'image_url',
        'name',
        'slug',
        'short_description',
        'description',
        'status',
    ];
}
