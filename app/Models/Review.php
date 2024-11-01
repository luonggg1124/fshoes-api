<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'title',
        'text',
        'rating',
    ];
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function likes():BelongsToMany
    {
        return $this->belongsToMany(User::class,'review_like','review_id','user_id');
    }
}
