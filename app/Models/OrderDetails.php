<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetails extends Model
{
    use HasFactory,softDeletes;
    protected $table = 'order_details';
    protected $fillable = [
        "order_id",
        "product_variation_id",
        "product_id",
        "price",
        "quantity",
        "total_amount",
    ];
    public  function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function variation():BelongsTo
    {
        return $this->belongsTo(ProductVariations::class,'product_variation_id');
    }
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
