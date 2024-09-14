<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetails extends Model
{
    use HasFactory;
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
}
