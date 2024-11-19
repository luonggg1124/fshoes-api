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
        "date_start",
        "date_end",
        "min_total_amount",
        "quantity",
        "status"
    ];
}
