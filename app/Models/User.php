<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use App\Models\User\UserAddress;
use App\Models\User\UserProfile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

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
        'avatar_url',
        'is_active',
        'status',
        'group_id'
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function profile():HasOne
    {
        return $this->hasOne(UserProfile::class);
    }
    public function interestingCategories():BelongsToMany
    {
        return $this->belongsToMany(Category::class,'user_interests','user_id','category_id');
    }
    public function favoriteProducts():BelongsToMany
    {
        return $this->belongsToMany(Product::class,'user_product','user_id','product_id');
    }
    public function addresses():HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
    public function group():BelongsTo
    {
        return $this->belongsTo(Groups::class);
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function orders():HasMany
    {
        return $this->hasMany(Order::class,'user_id');
    }
    public function carts():HasMany
    {
        return $this->hasMany(Cart::class,'user_id');
    }
    public function likedReviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'review_like', 'user_id', 'review_id');
    }
    public function voucherUsed():BelongsToMany
    {
        return $this->belongsToMany(Voucher::class, 'user_voucher', 'user_id', 'voucher_id');
    }
}
