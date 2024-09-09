<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\User\UserAvatar;
use App\Models\User\UserAddress;
use App\Models\User\UserProfile;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'is_admin',
        'is_active',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile():HasOne
    {
        return $this->hasOne(UserProfile::class);
    }
    public function interestingCategories():BelongsToMany
    {
        return $this->belongsToMany(Category::class,'user_interests','user_id','category_id');
    }
    public function addresses():HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
    public function allAvatars():HasMany
    {
        return $this->hasMany(UserAvatar::class,'user_id');
    }
    public function avatar()
    {
       
        $avatar = $this->allAvatars()->where('is_active',true)->latest()->first();
        if(!$avatar){
            return null;
        }
        return $avatar;
    }
}
