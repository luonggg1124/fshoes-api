<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'given_name',
        'family_name',
        'birth_date'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
