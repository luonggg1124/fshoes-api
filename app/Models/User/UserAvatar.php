<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAvatar extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'avatar_url',
        'cloudinary_public_id',
        'is_active'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
