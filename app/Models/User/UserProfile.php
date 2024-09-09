<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'given_name',
        'family_name',
        'address_active_id',
        'birth_date'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function addressActive():BelongsTo
    {
        return $this->belongsTo(UserAddress::class,'address_active_id');
    }
}
