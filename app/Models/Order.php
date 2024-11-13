<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Order extends Model
{

    use HasFactory, Searchable;

    protected $table = 'orders';
    protected $fillable = [
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
        "voucher_id",
        "address",
        "total_amount",
        "status"
    ];


    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function orderHistory(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function toSearchableArray()
    {
        return [
            "id" => $this->id,
            "address" => $this->address,
            "payment_method"=>$this->payment_method,
            "receiver_full_name" => $this->receiver_full_name,
            "city" => $this->city,
            "country" => $this->country
        ];
    }

}
