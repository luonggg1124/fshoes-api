<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'address',
        'phone_num',
        'name'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
