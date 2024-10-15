<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use HasFactory,softDeletes;
    protected $table='orders';
    protected $fillable=[
        "user_id",
        "payment_method",
        "payment_status",
        "shipping_method",
        "shipping_cost",
        "tax_amount",
        "amount_collected",
        "note",
        "receiver_full_name",
        "phone",
        "city",
        "country",
        "postal_code",
        "voucher_id",
        "address",
        "total_amount",
        "status"
    ];




    public function orderDetails():HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
    public function orderHistory() : HasMany
    {
            return $this->hasMany(OrderHistory::class );
    }
}
