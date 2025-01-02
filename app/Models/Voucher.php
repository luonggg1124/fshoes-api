<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'vouchers';
    protected $fillable=[
        "code",
        "discount",
        "type",
        "date_start",
        "date_end",
        "min_total_amount",
        "max_total_amount",
        "quantity",
        "status"
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_voucher','voucher_id','user_id');
    }
}
