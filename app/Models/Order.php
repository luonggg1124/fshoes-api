<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable=[
        "user_id",
        "total_amount",
        "payment_method",
        "payment_status",
        "shipping_method",
        "shipping_cost",
        "tax_amount",
        "amount_collected",
        "note",
        "status"
    ];
    public function orderDetails():HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
}
